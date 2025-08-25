<?php
/**
 * Plugin Name: WooCommerce Keyword Popups
 * Description: Simple shortcode to create clickable keywords with popup details in product descriptions
 * Version: 1.0.0
 * Author: Your Name
 * Text Domain: wc-keyword-popups
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class WC_Keyword_Popups {
    
    private static $modals = array();
    
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
        add_action('wp_footer', array($this, 'output_modals_and_scripts'), 20);
        
        // Add debug info if WP_DEBUG is enabled
        if (defined('WP_DEBUG') && WP_DEBUG) {
            add_action('wp_footer', array($this, 'debug_info'), 21);
        }
    }
    
    public function init() {
        // Register the shortcode
        add_shortcode('popup_keyword', array($this, 'popup_keyword_shortcode'));
    }
    
    /**
     * Shortcode handler
     */
    public function popup_keyword_shortcode($atts) {
        // Default attributes
        $atts = shortcode_atts(array(
            'text' => 'Click me',
            'content' => 'No content provided'
        ), $atts);
        
        // Sanitize attributes
        $text = sanitize_text_field($atts['text']);
        $content = wp_kses_post($atts['content']);
        
        // Generate unique ID for this popup
        $popup_id = 'popup_' . uniqid();
        
        // Store modal for footer output
        self::$modals[$popup_id] = $content;
        
        // Return only the trigger (inline)
        return sprintf(
            '<span class="keyword-popup-trigger" data-popup-id="%s">%s</span>',
            esc_attr($popup_id),
            esc_html($text)
        );
    }
    
    /**
     * Output all modals and scripts in footer
     */
    public function output_modals_and_scripts() {
        if (!empty(self::$modals)) {
            echo "\n<!-- Keyword Popup Modals -->\n";
            
            // Output modals
            foreach (self::$modals as $popup_id => $content) {
                echo '<div id="' . esc_attr($popup_id) . '" class="keyword-popup-modal" style="display:none;">' . "\n";
                echo '    <div class="keyword-popup-content">' . "\n";
                echo '        <span class="keyword-popup-close">&times;</span>' . "\n";
                echo '        <div class="keyword-popup-body">' . $content . '</div>' . "\n";
                echo '    </div>' . "\n";
                echo '</div>' . "\n";
            }
            
            echo "<!-- End Keyword Popup Modals -->\n\n";
            
            // Output JavaScript after modals
            $this->add_inline_scripts();
        }
    }
    
    /**
     * Enqueue only CSS
     */
    public function enqueue_styles() {
        // Load on all pages to ensure it works everywhere
        $this->add_inline_styles();
    }
    
    /**
     * Add inline CSS
     */
    private function add_inline_styles() {
        $css = '
        <style>
        .keyword-popup-trigger {
            color: #0073aa;
            text-decoration: underline;
            cursor: pointer;
            font-weight: 500;
            display: inline;
            margin: 0;
            padding: 0;
            line-height: inherit;
        }
        
        .keyword-popup-trigger:hover {
            color: #005a87;
            text-decoration: none;
        }
        
        .keyword-popup-modal {
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            display: none;
        }
        
        .keyword-popup-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 0;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            position: relative;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            animation: popup-fade-in 0.3s ease;
        }
        
        @keyframes popup-fade-in {
            from { opacity: 0; transform: scale(0.8); }
            to { opacity: 1; transform: scale(1); }
        }
        
        .keyword-popup-close {
            position: absolute;
            right: 15px;
            top: 10px;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            color: #999;
            z-index: 1;
        }
        
        .keyword-popup-close:hover {
            color: #333;
        }
        
        .keyword-popup-body {
            padding: 30px 20px 20px 20px;
            line-height: 1.6;
        }
        
        @media (max-width: 768px) {
            .keyword-popup-content {
                width: 95%;
                margin: 20% auto;
            }
            
            .keyword-popup-body {
                padding: 25px 15px 15px 15px;
            }
        }
        </style>';
        
        echo $css;
    }
    
    /**
     * Add inline JavaScript
     */
    private function add_inline_scripts() {
        $js = "
        <script>
        (function() {
            'use strict';
            
            function initKeywordPopups() {
                console.log('Initializing keyword popups...');
                
                // Handle keyword clicks
                document.addEventListener('click', function(e) {
                    if (e.target && e.target.classList.contains('keyword-popup-trigger')) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        var popupId = e.target.getAttribute('data-popup-id');
                        console.log('Clicked trigger with popup ID:', popupId);
                        
                        if (popupId) {
                            var modal = document.getElementById(popupId);
                            if (modal) {
                                console.log('Opening modal:', modal);
                                modal.style.display = 'block';
                                document.body.style.overflow = 'hidden';
                            } else {
                                console.error('Modal not found for ID:', popupId);
                            }
                        }
                    }
                    
                    // Handle close button clicks
                    if (e.target && e.target.classList.contains('keyword-popup-close')) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        var modal = e.target.closest('.keyword-popup-modal');
                        if (modal) {
                            console.log('Closing modal via close button');
                            modal.style.display = 'none';
                            document.body.style.overflow = 'auto';
                        }
                    }
                    
                    // Close modal when clicking backdrop
                    if (e.target && e.target.classList.contains('keyword-popup-modal')) {
                        console.log('Closing modal via backdrop click');
                        e.target.style.display = 'none';
                        document.body.style.overflow = 'auto';
                    }
                });
                
                // Close modal with Escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' || e.keyCode === 27) {
                        var openModals = document.querySelectorAll('.keyword-popup-modal');
                        var hasOpenModal = false;
                        
                        openModals.forEach(function(modal) {
                            if (modal.style.display === 'block' || window.getComputedStyle(modal).display === 'block') {
                                modal.style.display = 'none';
                                hasOpenModal = true;
                            }
                        });
                        
                        if (hasOpenModal) {
                            console.log('Closing modal via Escape key');
                            document.body.style.overflow = 'auto';
                        }
                    }
                });
                
                console.log('Keyword popups initialized successfully');
            }
            
            // Initialize when DOM is ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initKeywordPopups);
            } else {
                initKeywordPopups();
            }
        })();
        </script>";
        
        echo $js;
    }
    
    /**
     * Debug information (only when WP_DEBUG is enabled)
     */
    public function debug_info() {
        if (!empty(self::$modals)) {
            echo "\n<!-- Debug: Keyword Popup Plugin Info -->\n";
            echo "<!-- Registered modals: " . count(self::$modals) . " -->\n";
            echo "<!-- Modal IDs: " . implode(', ', array_keys(self::$modals)) . " -->\n";
            echo "<!-- End Debug Info -->\n\n";
        }
    }
}

// Initialize the plugin
new WC_Keyword_Popups();