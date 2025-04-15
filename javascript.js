// Wait for DOM to fully load
document.addEventListener('DOMContentLoaded', function() {
    // Center all headings
    centerAllHeadings();
    
    // Set up image click handlers for gallery
    setupImageZoom();
    
    // Highlight active navigation link
    highlightActiveNavLink();
});

// Function to center all headings
function centerAllHeadings() {
    const headings = document.querySelectorAll('h1, h2, h3');
    headings.forEach(heading => {
        heading.style.textAlign = 'center';
    });
}

// Function to setup image zoom functionality
function setupImageZoom() {
    // Only run on gallery page
    if (window.location.href.includes('gallery.html')) {
        const images = document.querySelectorAll('img');
        
        // Create modal elements if they don't exist
        if (!document.getElementById('imageModal')) {
            createImageModal();
        }
        
        // Add click event to each image
        images.forEach(img => {
            img.style.cursor = 'pointer';
            img.addEventListener('click', function() {
                openImageModal(this.src);
            });
        });
    }
}

// Create modal for image zoom
function createImageModal() {
    const modal = document.createElement('div');
    modal.id = 'imageModal';
    modal.style.display = 'none';
    modal.style.position = 'fixed';
    modal.style.zIndex = '1000';
    modal.style.left = '0';
    modal.style.top = '0';
    modal.style.width = '100%';
    modal.style.height = '100%';
    modal.style.backgroundColor = 'rgba(0,0,0,0.9)';
    modal.style.padding = '20px';
    
    const closeBtn = document.createElement('span');
    closeBtn.innerHTML = '&times;';
    closeBtn.style.position = 'absolute';
    closeBtn.style.top = '20px';
    closeBtn.style.right = '30px';
    closeBtn.style.color = 'white';
    closeBtn.style.fontSize = '40px';
    closeBtn.style.fontWeight = 'bold';
    closeBtn.style.cursor = 'pointer';
    
    const img = document.createElement('img');
    img.id = 'enlargedImage';
    img.style.display = 'block';
    img.style.maxWidth = '90%';
    img.style.maxHeight = '90%';
    img.style.margin = '0 auto';
    img.style.marginTop = '50px';
    img.style.border = '5px solid white';
    img.style.borderRadius = '10px';
    
    // Add click events
    closeBtn.addEventListener('click', closeImageModal);
    modal.addEventListener('click', closeImageModal);
    
    // Prevent closing when clicking on the image itself
    img.addEventListener('click', function(event) {
        event.stopPropagation();
    });
    
    modal.appendChild(closeBtn);
    modal.appendChild(img);
    document.body.appendChild(modal);
}

// Open the modal with the clicked image
function openImageModal(imgSrc) {
    const modal = document.getElementById('imageModal');
    const enlargedImg = document.getElementById('enlargedImage');
    
    modal.style.display = 'block';
    enlargedImg.src = imgSrc;
}

// Close the modal
function closeImageModal() {
    document.getElementById('imageModal').style.display = 'none';
}

// Highlight the active navigation link based on current page
function highlightActiveNavLink() {
    const currentPage = window.location.href.split('/').pop();
    const navLinks = document.querySelectorAll('nav a');
    
    navLinks.forEach(link => {
        // Remove any existing active class
        link.classList.remove('active');
        
        // Add active class if href matches current page
        if (link.getAttribute('href') === currentPage || 
           (currentPage === '' && link.getAttribute('href') === 'index.html')) {
            link.classList.add('active');
            link.style.backgroundColor = '#ff85a2';
            link.style.color = 'white';
        }
    });
}