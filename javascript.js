// Wait for DOM to fully load
document.addEventListener('DOMContentLoaded', function() {
    // Apply warm styling and animations
    applyWarmStyling();
    
    // Center all headings with a gentle animation
    centerAllHeadings();
    
    // Set up image hover and click effects for gallery
    setupImageEffects();
    
    // Highlight active navigation link with animation
    highlightActiveNavLink();
    
    // Add heartbeat effect to family quotes
    addHeartbeatToQuotes();
    
    // Add gentle fade-in for page elements
    fadeInPageElements();
    
    // Add subtle floating animation to family photos
    addFloatingEffect();
});

// Function to apply warm styling to the entire site
function applyWarmStyling() {
    // Add a warm background gradient to the body if not already styled in CSS
    document.body.style.background = "linear-gradient(to bottom, #fff8f0, #fff2e6)";
    
    // Add a subtle text shadow to headings for warmth
    const headings = document.querySelectorAll('h1, h2, h3');
    headings.forEach(heading => {
        heading.style.textShadow = "1px 1px 3px rgba(244, 164, 96, 0.3)";
        heading.style.transition = "all 0.5s ease";
    });
    
    // Add a warm border to images
    const images = document.querySelectorAll('img');
    images.forEach(img => {
        img.style.border = "5px solid #fff";
        img.style.boxShadow = "0 4px 8px rgba(244, 164, 96, 0.5)";
        img.style.transition = "all 0.3s ease-in-out";
    });
}

// Function to center all headings with animation
function centerAllHeadings() {
    const headings = document.querySelectorAll('h1, h2, h3');
    headings.forEach((heading, index) => {
        heading.style.textAlign = 'center';
        heading.style.opacity = '0';
        heading.style.transform = 'translateY(20px)';
        heading.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
        
        // Delayed appearance for sequential animation
        setTimeout(() => {
            heading.style.opacity = '1';
            heading.style.transform = 'translateY(0)';
        }, 300 + (index * 150));
    });
}

// Function to setup image effects
function setupImageEffects() {
    const images = document.querySelectorAll('img');
    
    // Create modal elements if they don't exist (for gallery)
    if (!document.getElementById('imageModal')) {
        createImageModal();
    }
    
    // Add hover and click effects to each image
    images.forEach(img => {
        // Styling
        img.style.cursor = 'pointer';
        img.style.transition = 'all 0.4s ease-in-out';
        
        // Hover effect
        img.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.05) rotate(1deg)';
            this.style.boxShadow = '0 8px 16px rgba(244, 164, 96, 0.7)';
        });
        
        img.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1) rotate(0deg)';
            this.style.boxShadow = '0 4px 8px rgba(244, 164, 96, 0.5)';
        });
        
        // Click event for zoom effect
        img.addEventListener('click', function() {
            // Only use modal on gallery page
            if (window.location.href.includes('gallery.html')) {
                openImageModal(this.src);
            }
        });
    });
}

// Create enhanced modal for image zoom with family-friendly styling
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
    modal.style.backgroundColor = 'rgba(255, 245, 235, 0.95)';
    modal.style.padding = '20px';
    modal.style.transition = 'all 0.4s ease-in-out';
    
    const closeBtn = document.createElement('span');
    closeBtn.innerHTML = '&times;';
    closeBtn.style.position = 'absolute';
    closeBtn.style.top = '20px';
    closeBtn.style.right = '30px';
    closeBtn.style.color = '#ff85a2';
    closeBtn.style.fontSize = '40px';
    closeBtn.style.fontWeight = 'bold';
    closeBtn.style.cursor = 'pointer';
    closeBtn.style.transition = 'all 0.3s ease';
    
    // Add heart icon near the image
    const heartIcon = document.createElement('div');
    heartIcon.innerHTML = '❤️';
    heartIcon.style.position = 'absolute';
    heartIcon.style.bottom = '20px';
    heartIcon.style.right = '30px';
    heartIcon.style.fontSize = '32px';
    heartIcon.style.animation = 'pulse 1.5s infinite';
    
    // Create caption for the image
    const caption = document.createElement('div');
    caption.id = 'imageCaption';
    caption.style.color = '#ff85a2';
    caption.style.textAlign = 'center';
    caption.style.padding = '15px';
    caption.style.fontFamily = 'cursive, sans-serif';
    caption.style.fontSize = '20px';
    caption.style.marginTop = '10px';
    caption.textContent = 'Family is everything ❤️';
    
    const img = document.createElement('img');
    img.id = 'enlargedImage';
    img.style.display = 'block';
    img.style.maxWidth = '80%';
    img.style.maxHeight = '80%';
    img.style.margin = '0 auto';
    img.style.marginTop = '30px';
    img.style.border = '8px solid white';
    img.style.borderRadius = '15px';
    img.style.boxShadow = '0 10px 25px rgba(244, 164, 96, 0.7)';
    img.style.transform = 'scale(0.9)';
    img.style.opacity = '0';
    img.style.transition = 'all 0.5s ease-in-out';
    
    // Add keyframes for pulsing animation
    const style = document.createElement('style');
    style.innerHTML = `
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
    `;
    document.head.appendChild(style);
    
    // Add hover effect to close button
    closeBtn.addEventListener('mouseenter', function() {
        this.style.transform = 'scale(1.2) rotate(5deg)';
        this.style.color = '#ff6b88';
    });
    
    closeBtn.addEventListener('mouseleave', function() {
        this.style.transform = 'scale(1) rotate(0deg)';
        this.style.color = '#ff85a2';
    });
    
    // Add click events
    closeBtn.addEventListener('click', closeImageModal);
    modal.addEventListener('click', closeImageModal);
    
    // Prevent closing when clicking on the image itself
    img.addEventListener('click', function(event) {
        event.stopPropagation();
    });
    
    modal.appendChild(closeBtn);
    modal.appendChild(img);
    modal.appendChild(caption);
    modal.appendChild(heartIcon);
    document.body.appendChild(modal);
}

// Open the modal with the clicked image
function openImageModal(imgSrc) {
    const modal = document.getElementById('imageModal');
    const enlargedImg = document.getElementById('enlargedImage');
    const imageCaption = document.getElementById('imageCaption');
    
    // Get the alt text from the image if possible
    const originalImg = document.querySelector(`img[src="${imgSrc}"]`);
    const altText = originalImg ? originalImg.alt : '';
    
    // Set custom captions based on the image
    if (imgSrc.includes('photo1')) {
        imageCaption.textContent = 'Hangat, harmonis, dan membanggakan ❤️';
    } else if (imgSrc.includes('photo2')) {
        imageCaption.textContent = 'Penuh kebanggaan, meriah, dan kompak ✨';
    } else if (imgSrc.includes('photo3')) {
        imageCaption.textContent = 'Resmi, rapi, dan penuh makna 💙';
    } else {
        imageCaption.textContent = altText || 'Keluarga adalah segalanya ❤️';
    }
    
    modal.style.display = 'block';
    enlargedImg.src = imgSrc;
    
    // Add a small delay for the animation to work properly
    setTimeout(() => {
        enlargedImg.style.opacity = '1';
        enlargedImg.style.transform = 'scale(1)';
    }, 50);
}

// Close the modal with animation
function closeImageModal() {
    const modal = document.getElementById('imageModal');
    const enlargedImg = document.getElementById('enlargedImage');
    
    enlargedImg.style.opacity = '0';
    enlargedImg.style.transform = 'scale(0.9)';
    
    setTimeout(() => {
        modal.style.display = 'none';
    }, 300);
}

// Highlight the active navigation link with animation
function highlightActiveNavLink() {
    const currentPage = window.location.href.split('/').pop();
    const navLinks = document.querySelectorAll('nav a');
    
    navLinks.forEach(link => {
        // Remove any existing active class
        link.classList.remove('active');
        
        // Style all links with warm colors
        link.style.transition = 'all 0.3s ease-in-out';
        link.style.borderRadius = '8px';
        
        // Add hover effect
        link.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px)';
            this.style.boxShadow = '0 4px 8px rgba(255, 133, 162, 0.5)';
        });
        
        link.addEventListener('mouseleave', function() {
            if (!this.classList.contains('active')) {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'none';
            }
        });
        
        // Add active class if href matches current page
        if (link.getAttribute('href') === currentPage || 
           (currentPage === '' && link.getAttribute('href') === 'index.html')) {
            link.classList.add('active');
            link.style.backgroundColor = '#ff85a2';
            link.style.color = 'white';
            link.style.transform = 'translateY(-3px)';
            link.style.boxShadow = '0 4px 8px rgba(255, 133, 162, 0.5)';
        }
    });
}

// Add heartbeat effect to family quotes
function addHeartbeatToQuotes() {
    const quotes = document.querySelectorAll('p:first-of-type');
    
    quotes.forEach(quote => {
        if (quote.textContent.includes('"')) {
            // Add heart icons to quotes
            if (!quote.innerHTML.includes('❤️')) {
                quote.innerHTML = quote.innerHTML.replace('"', '"❤️ ');
            }
            
            // Add styling and animation
            quote.style.fontFamily = 'cursive, sans-serif';
            quote.style.fontSize = '1.1em';
            quote.style.color = '#ff6b88';
            quote.style.padding = '15px';
            quote.style.border = '1px dashed #ffb6c1';
            quote.style.borderRadius = '10px';
            quote.style.margin = '20px auto';
            quote.style.maxWidth = '90%';
            quote.style.textAlign = 'center';
            quote.style.backgroundColor = 'rgba(255, 245, 245, 0.7)';
            quote.style.position = 'relative';
            quote.style.animation = 'float 5s ease-in-out infinite';
        }
    });
}

// Add fade-in effect for page elements
function fadeInPageElements() {
    const mainContent = document.querySelector('main');
    const sections = document.querySelectorAll('section');
    const paragraphs = document.querySelectorAll('p');
    
    // Fade in main content
    if (mainContent) {
        mainContent.style.opacity = '0';
        mainContent.style.transition = 'opacity 1s ease-in-out';
        
        setTimeout(() => {
            mainContent.style.opacity = '1';
        }, 200);
    }
    
    // Fade in sections with delay
    sections.forEach((section, index) => {
        section.style.opacity = '0';
        section.style.transform = 'translateY(20px)';
        section.style.transition = 'opacity 0.8s ease-in-out, transform 0.8s ease-in-out';
        
        setTimeout(() => {
            section.style.opacity = '1';
            section.style.transform = 'translateY(0)';
        }, 400 + (index * 200));
    });
    
    // Fade in paragraphs with delay
    paragraphs.forEach((paragraph, index) => {
        // Skip quotes as they already have animations
        if (!paragraph.textContent.includes('"')) {
            paragraph.style.opacity = '0';
            paragraph.style.transform = 'translateY(15px)';
            paragraph.style.transition = 'opacity 0.6s ease-in-out, transform 0.6s ease-in-out';
            
            setTimeout(() => {
                paragraph.style.opacity = '1';
                paragraph.style.transform = 'translateY(0)';
            }, 600 + (index * 150));
        }
    });
}

// Add floating effect to family photos
function addFloatingEffect() {
    // Target specifically the family photos
    const familyPhotos = document.querySelectorAll('.gallery-container img, .profile img');
    
    familyPhotos.forEach((img, index) => {
        // Add a floating animation with different timing for each photo
        img.style.animation = `float ${3 + index * 0.5}s ease-in-out infinite`;
        img.style.animationDelay = `${index * 0.3}s`;
        
        // Add hover interaction
        img.addEventListener('mouseenter', function() {
            this.style.animationPlayState = 'paused';
        });
        
        img.addEventListener('mouseleave', function() {
            this.style.animationPlayState = 'running';
        });
    });
}
