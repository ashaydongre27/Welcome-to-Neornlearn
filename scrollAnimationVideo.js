const video = document.getElementById('video');

        // Wait for video to load before enabling scroll control
        video.addEventListener('loadedmetadata', function() {
            
            // Main scroll handler
            window.addEventListener('scroll', function() {
                
                // Calculate how far user has scrolled (0 to 1)
                const maxScroll = document.body.scrollHeight - window.innerHeight;
                const scrollPercent = window.scrollY / maxScroll;
                
                // Map scroll position to video time
                // scrollPercent 0 = video start, scrollPercent 1 = video end
                const videoTime = scrollPercent * video.duration;
                
                // Update video time
                video.currentTime = videoTime;
            });
        });