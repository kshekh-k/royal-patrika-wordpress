// PDF Viewer Script
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all PDF viewers on the page
    const wrappers = document.querySelectorAll('.fpv-wrapper');
    
    wrappers.forEach(wrapper => {
        initPdfViewer(wrapper);
    });
});

function initPdfViewer(wrapper) {
    const url = wrapper.dataset.url;
    const height = wrapper.dataset.height;
    const canvas = wrapper.querySelector('.pdf-canvas');
    const ctx = canvas.getContext('2d');
    const loader = wrapper.querySelector('.fpv-loader');
    const pageNumSpan = wrapper.querySelector('.page_num');
    const pageCountSpan = wrapper.querySelector('.page_count');
    
    // PDF viewer state
    let pdfDoc = null;
    let pageNum = 1;
    let scale = 1.2;
    const MAX_SCALE = 3;
    const MIN_SCALE = 0.4;
    let pageRendering = false;
    let pageNumPending = null;
    const pageCache = new Map();

    // Check if PDF.js is loaded
    if (typeof pdfjsLib === 'undefined') {
        console.error('PDF.js library not loaded');
        showError('PDF.js library not loaded. Please check your connection.');
        return;
    }

    // Set PDF.js worker
    if (typeof fpv_vars !== 'undefined' && fpv_vars.pdf_worker) {
        pdfjsLib.GlobalWorkerOptions.workerSrc = fpv_vars.pdf_worker;
    } else {
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
    }

    // Disable image smoothing for better text rendering
    ctx.imageSmoothingEnabled = false;

    function showLoader(show) {
        if (loader) {
            loader.style.display = show ? 'block' : 'none';
        }
    }

    function showError(message) {
        if (loader) {
            loader.innerHTML = message;
            loader.style.display = 'block';
            loader.style.color = 'red';
        }
    }

    function updatePageInfo() {
        if (pageNumSpan) pageNumSpan.textContent = pageNum;
        if (pageCountSpan && pdfDoc) pageCountSpan.textContent = pdfDoc.numPages;
    }

    function renderPage(num) {
        if (pageRendering) {
            pageNumPending = num;
            return;
        }

        pageRendering = true;
        showLoader(true);

        const cacheKey = `${num}_${scale}`;
        
        // Check cache first
        if (pageCache.has(cacheKey)) {
            const cachedData = pageCache.get(cacheKey);
            canvas.width = cachedData.width;
            canvas.height = cachedData.height;
            ctx.putImageData(cachedData.imageData, 0, 0);
            pageRendering = false;
            showLoader(false);
            updatePageInfo();
            
            if (pageNumPending !== null) {
                const pending = pageNumPending;
                pageNumPending = null;
                renderPage(pending);
            }
            return;
        }

        // Render new page
        pdfDoc.getPage(num).then(function(page) {
            const viewport = page.getViewport({ scale: scale });
            
            // Set canvas dimensions
            canvas.height = viewport.height;
            canvas.width = viewport.width;
            
            // Set canvas container styling
            const container = wrapper.querySelector('.fpv-canvas-container');
            if (container) {
                canvas.style.display = 'block';
                canvas.style.maxWidth = '100%';
                canvas.style.height = 'auto';
            }

            const renderContext = {
                canvasContext: ctx,
                viewport: viewport,
                intent: 'display'
            };

            return page.render(renderContext).promise;
        }).then(function() {
            // Cache the rendered page (limit cache size)
            if (pageCache.size > 10) {
                const firstKey = pageCache.keys().next().value;
                pageCache.delete(firstKey);
            }
            
            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            pageCache.set(cacheKey, {
                imageData: imageData,
                width: canvas.width,
                height: canvas.height
            });

            pageRendering = false;
            showLoader(false);
            updatePageInfo();

            // Render any pending page
            if (pageNumPending !== null) {
                const pending = pageNumPending;
                pageNumPending = null;
                renderPage(pending);
            }
        }).catch(function(error) {
            console.error('Error rendering page:', error);
            showError('Error rendering page: ' + error.message);
            pageRendering = false;
        });
    }

    function queueRenderPage(num) {
        if (pageRendering) {
            pageNumPending = num;
        } else {
            renderPage(num);
        }
    }

    function goToPrevPage() {
        if (pageNum > 1) {
            pageNum--;
            queueRenderPage(pageNum);
        }
    }

    function goToNextPage() {
        if (pdfDoc && pageNum < pdfDoc.numPages) {
            pageNum++;
            queueRenderPage(pageNum);
        }
    }

    function zoomIn() {
        if (scale < MAX_SCALE) {
            scale = Math.min(scale + 0.2, MAX_SCALE);
            pageCache.clear(); // Clear cache when scale changes
            queueRenderPage(pageNum);
        }
    }

    function zoomOut() {
        if (scale > MIN_SCALE) {
            scale = Math.max(scale - 0.2, MIN_SCALE);
            pageCache.clear(); // Clear cache when scale changes
            queueRenderPage(pageNum);
        }
    }

    function printPdf() {
        const printWindow = window.open(url, '_blank');
        if (printWindow) {
            printWindow.onload = function() {
                printWindow.print();
            };
        }
    }

    // Event listeners
    const prevBtn = wrapper.querySelector('.fpv-prev');
    const nextBtn = wrapper.querySelector('.fpv-next');
    const zoomInBtn = wrapper.querySelector('.fpv-zoom-in');
    const zoomOutBtn = wrapper.querySelector('.fpv-zoom-out');
    const printBtn = wrapper.querySelector('.fpv-print');

    if (prevBtn) prevBtn.addEventListener('click', goToPrevPage);
    if (nextBtn) nextBtn.addEventListener('click', goToNextPage);
    if (zoomInBtn) zoomInBtn.addEventListener('click', zoomIn);
    if (zoomOutBtn) zoomOutBtn.addEventListener('click', zoomOut);
    if (printBtn) printBtn.addEventListener('click', printPdf);

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.target.closest('.fpv-wrapper') === wrapper) {
            switch(e.key) {
                case 'ArrowLeft':
                    e.preventDefault();
                    goToPrevPage();
                    break;
                case 'ArrowRight':
                    e.preventDefault();
                    goToNextPage();
                    break;
                case '+':
                case '=':
                    e.preventDefault();
                    zoomIn();
                    break;
                case '-':
                    e.preventDefault();
                    zoomOut();
                    break;
            }
        }
    });

    // Load PDF
    showLoader(true);
    
    const loadingTask = pdfjsLib.getDocument({
        url: url,
        rangeChunkSize: 131072,
        disableAutoFetch: true,
        disableStream: false,
        disableRange: false,
        maxImageSize: 1024 * 1024 * 10,
        verbosity: 0
    });

    fetch(url)
  .then(response => response.arrayBuffer())
  .then(data => {
    return pdfjsLib.getDocument({ data }).promise;
  })
  .then(function(pdf) {
    pdfDoc = pdf;
    updatePageInfo();
    renderPage(pageNum);
    console.log('PDF loaded successfully:', pdf.numPages, 'pages');
  })
  .catch(function(error) {
    console.error('Error loading PDF:', error);
    showError('Error loading PDF: ' + error.message + '. Please check the URL and try again.');
  });


    loadingTask.promise.then(function(pdf) {
        pdfDoc = pdf;
        updatePageInfo();
        renderPage(pageNum);
        console.log('PDF loaded successfully:', pdf.numPages, 'pages');
    }).catch(function(error) {
        console.error('Error loading PDF:', error);
        showError('Error loading PDF: ' + error.message + '. Please check the URL and try again.');
    });

    // Clean up on page unload
    window.addEventListener('beforeunload', function() {
        pageCache.clear();
    });
}