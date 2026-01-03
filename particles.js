// Particles.js Configuration
particlesJS('particles-js', {
    // Particle Settings
    particles: {
        number: {
            value: 100,
            density: {
                enable: true,
                value_area: 800
            }
        },
        color: {
            value: '#ffffff'
        },
        shape: {
            type: 'circle',
            stroke: {
                width: 0,
                color: '#000000'
            },
            polygon: {
                nb_sides: 5
            }
        },
        opacity: {
            value: 0.5,
            random: true,
            anim: {
                enable: true,
                speed: 3,
                opacity_min: 0.1,
                sync: false
            }
        },
        size: {
            value: 4,
            random: true,
            anim: {
                enable: true,
                speed: 5,
                size_min: 0.1,
                sync: false
            }
        },
        line_linked: {
            enable: true,
            distance: 200,
            color: '#EBD3F8',
            opacity: 1,
            width: 1
        },
        move: {
            enable: true,
            speed: 2,
            direction: 'none',
            random: false,
            straight: false,
            out_mode: 'out',
            bounce: false,
            attract: {
                enable: false,
                rotateX: 600,
                rotateY: 1200
            }
        }
    },

    // Interactivity Settings
    interactivity: {
        detect_on: 'canvas',
        events: {
            onhover: {
                enable: true,
                mode: 'grab'
            },
            onclick: {
                enable: true,
                mode: 'push'
            },
            resize: true
        },
        modes: {
            grab: {
                distance: 50,
                line_linked: {
                    opacity: 1
                }
            },
            bubble: {
                distance: 100,
                size: 40,
                duration: 2,
                opacity: 8,
                speed: 3
            },
            repulse: {
                distance: 200,
                duration: 0.9
            },
            push: {
                particles_nb: 4
            },
            remove: {
                particles_nb: 2
            }
        }
    },

    // Retina Display Support
    retina_detect: true
});

// Optional: Button Click Handler
document.querySelector('.btn').addEventListener('click', function() {
    alert('Button Clicked! Customize this action.');
});

// Optional: Log when particles are loaded
console.log('Particles.js loaded successfully!');