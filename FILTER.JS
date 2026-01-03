// ============================================
// FILTER FUNCTIONALITY
// ============================================

// Toggle filter section open/close
function toggleFilter(header) {
    header.classList.toggle('active');
    const content = header.nextElementSibling;
    content.classList.toggle('active');
}

// Toggle sidebar on mobile
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    sidebar.classList.toggle('active');
    overlay.classList.toggle('active');
}

// Add keyword to search
function addKeyword(tag) {
    const searchInput = document.getElementById('keywordSearch');
    tag.classList.toggle('active');
    
    // Get all active keywords
    const activeKeywords = document.querySelectorAll('.keyword-tag.active');
    const keywords = Array.from(activeKeywords).map(k => k.textContent);
    
    // Add to search input
    searchInput.value = keywords.join(' ');
    
    // Filter courses
    filterCourses();
}

// Update price display from slider
function updatePriceDisplay() {
    const slider = document.getElementById('priceSlider');
    const maxPriceInput = document.getElementById('maxPrice');
    const maxPriceDisplay = document.getElementById('maxPriceDisplay');
    
    maxPriceInput.value = slider.value;
    maxPriceDisplay.textContent = '₹' + slider.value;
    
    filterCourses();
}

// Main filter function
function filterCourses() {
    const courses = document.querySelectorAll('.course-card');
    const keyword = document.getElementById('keywordSearch').value.toLowerCase();
    
    // Get selected categories
    const categoryCheckboxes = document.querySelectorAll('.filter-section:nth-child(2) input[type="checkbox"]:checked');
    const selectedCategories = Array.from(categoryCheckboxes).map(cb => cb.value);
    
    // Get selected levels
    const levelCheckboxes = document.querySelectorAll('.filter-section:nth-child(3) input[type="checkbox"]:checked');
    const selectedLevels = Array.from(levelCheckboxes).map(cb => cb.value);
    
    // Get price range
    const minPrice = parseInt(document.getElementById('minPrice').value) || 0;
    const maxPrice = parseInt(document.getElementById('maxPrice').value) || 10000;
    
    // Get free/paid filter
    const freeCheckbox = document.querySelector('input[value="free"]');
    const paidCheckbox = document.querySelector('input[value="paid"]');
    const showFree = freeCheckbox ? freeCheckbox.checked : false;
    const showPaid = paidCheckbox ? paidCheckbox.checked : false;
    
    // Get selected ratings
    const ratingCheckboxes = document.querySelectorAll('.filter-section:nth-child(5) input[type="checkbox"]:checked');
    const selectedRatings = Array.from(ratingCheckboxes).map(cb => parseFloat(cb.value));
    const minRating = selectedRatings.length > 0 ? Math.min(...selectedRatings) : 0;
    
    let visibleCount = 0;
    
    courses.forEach(course => {
        const courseKeywords = course.dataset.keywords.toLowerCase();
        const courseTitle = course.querySelector('h3').textContent.toLowerCase();
        const courseCategory = course.dataset.category;
        const courseLevel = course.dataset.level;
        const coursePrice = parseInt(course.dataset.price);
        const courseRating = parseFloat(course.dataset.rating);
        
        let showCourse = true;
        
        // Keyword filter
        if (keyword) {
            const keywordTerms = keyword.split(' ').filter(k => k.length > 0);
            const matchesKeyword = keywordTerms.some(term => 
                courseKeywords.includes(term) || courseTitle.includes(term)
            );
            if (!matchesKeyword) showCourse = false;
        }
        
        // Category filter
        if (selectedCategories.length > 0 && !selectedCategories.includes(courseCategory)) {
            showCourse = false;
        }
        
        // Level filter
        if (selectedLevels.length > 0 && !selectedLevels.includes(courseLevel)) {
            showCourse = false;
        }
        
        // Price filter
        if (coursePrice < minPrice || coursePrice > maxPrice) {
            showCourse = false;
        }
        
        // Free/Paid filter
        if (showFree && !showPaid && coursePrice > 0) {
            showCourse = false;
        }
        if (showPaid && !showFree && coursePrice === 0) {
            showCourse = false;
        }
        
        // Rating filter
        if (minRating > 0 && courseRating < minRating) {
            showCourse = false;
        }
        
        // Show/Hide course
        if (showCourse) {
            course.classList.remove('hidden');
            visibleCount++;
        } else {
            course.classList.add('hidden');
        }
    });
    
    // Update course count
    document.getElementById('courseCount').textContent = visibleCount;
    
    // Show/hide no results message
    const noResults = document.getElementById('noResults');
    const courseGrid = document.getElementById('courseGrid');
    
    if (visibleCount === 0) {
        noResults.style.display = 'block';
        courseGrid.style.display = 'none';
    } else {
        noResults.style.display = 'none';
        courseGrid.style.display = 'grid';
    }
    
    // Update active filters display
    updateActiveFilters();
}

// Update active filters display
function updateActiveFilters() {
    const container = document.getElementById('activeFilters');
    container.innerHTML = '';
    
    // Keyword filter
    const keyword = document.getElementById('keywordSearch').value;
    if (keyword) {
        addFilterTag(container, 'Keyword: ' + keyword, () => {
            document.getElementById('keywordSearch').value = '';
            document.querySelectorAll('.keyword-tag.active').forEach(t => t.classList.remove('active'));
            filterCourses();
        });
    }
    
    // Category filters
    document.querySelectorAll('.filter-section:nth-child(2) input[type="checkbox"]:checked').forEach(cb => {
        const label = cb.parentElement.querySelector('.checkbox-label').textContent;
        addFilterTag(container, label, () => {
            cb.checked = false;
            filterCourses();
        });
    });
    
    // Level filters
    document.querySelectorAll('.filter-section:nth-child(3) input[type="checkbox"]:checked').forEach(cb => {
        const label = cb.parentElement.querySelector('.checkbox-label').textContent;
        addFilterTag(container, label, () => {
            cb.checked = false;
            filterCourses();
        });
    });
    
    // Rating filters
    document.querySelectorAll('.filter-section:nth-child(5) input[type="checkbox"]:checked').forEach(cb => {
        const label = cb.parentElement.querySelector('.checkbox-label').textContent;
        addFilterTag(container, label, () => {
            cb.checked = false;
            filterCourses();
        });
    });
}

// Add filter tag to active filters
function addFilterTag(container, text, removeCallback) {
    const tag = document.createElement('div');
    tag.className = 'active-filter-tag';
    tag.innerHTML = `${text} <i class="fas fa-times"></i>`;
    tag.querySelector('i').addEventListener('click', removeCallback);
    container.appendChild(tag);
}

// Clear all filters
function clearAllFilters() {
    // Clear keyword search
    document.getElementById('keywordSearch').value = '';
    
    // Clear keyword tags
    document.querySelectorAll('.keyword-tag.active').forEach(tag => {
        tag.classList.remove('active');
    });
    
    // Clear all checkboxes
    document.querySelectorAll('.sidebar input[type="checkbox"]').forEach(cb => {
        cb.checked = false;
    });
    
    // Reset price range
    document.getElementById('minPrice').value = 0;
    document.getElementById('maxPrice').value = 10000;
    document.getElementById('priceSlider').value = 10000;
    document.getElementById('maxPriceDisplay').textContent = '₹10000';
    
    // Reset sort
    document.getElementById('sortSelect').value = 'popular';
    
    // Re-filter (show all)
    filterCourses();
}

// Sort courses
function sortCourses() {
    const grid = document.getElementById('courseGrid');
    const courses = Array.from(grid.querySelectorAll('.course-card'));
    const sortBy = document.getElementById('sortSelect').value;
    
    courses.sort((a, b) => {
        const priceA = parseInt(a.dataset.price);
        const priceB = parseInt(b.dataset.price);
        const ratingA = parseFloat(a.dataset.rating);
        const ratingB = parseFloat(b.dataset.rating);
        
        switch (sortBy) {
            case 'price-low':
                return priceA - priceB;
            case 'price-high':
                return priceB - priceA;
            case 'rating':
                return ratingB - ratingA;
            case 'newest':
                // For demo, reverse order
                return -1;
            default: // popular
                return 0;
        }
    });
    
    // Re-append sorted courses
    courses.forEach(course => grid.appendChild(course));
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    filterCourses();
});