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
    
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
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
        
        // Return HTML
        return sprintf(
            '<span class="keyword-popup-trigger" data-popup-id="%s">%s</span>
            <div id="%s" class="keyword-popup-modal" style="display:none;">
                <div class="keyword-popup-content">
                    <span class="keyword-popup-close">&times;</span>
                    <div class="keyword-popup-body">%s</div>
                </div>
            </div>',
            esc_attr($popup_id),
            esc_html($text),
            esc_attr($popup_id),
            $content
        );
    }
    
    /**
     * Enqueue CSS and JavaScript
     */
    public function enqueue_scripts() {
        // Only load on pages that might have the shortcode
        if (is_product() || is_shop() || is_product_category() || is_product_tag()) {
            $this->add_inline_styles();
            $this->add_inline_scripts();
        }
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
        document.addEventListener('DOMContentLoaded', function() {
            // Handle keyword clicks
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('keyword-popup-trigger')) {
                    e.preventDefault();
                    var popupId = e.target.getAttribute('data-popup-id');
                    var modal = document.getElementById(popupId);
                    if (modal) {
                        modal.style.display = 'block';
                        document.body.style.overflow = 'hidden';
                    }
                }
                
                // Handle close button clicks
                if (e.target.classList.contains('keyword-popup-close')) {
                    var modal = e.target.closest('.keyword-popup-modal');
                    if (modal) {
                        modal.style.display = 'none';
                        document.body.style.overflow = 'auto';
                    }
                }
            });
            
            // Close modal when clicking outside
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('keyword-popup-modal')) {
                    e.target.style.display = 'none';
                    document.body.style.overflow = 'auto';
                }
            });
            
            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    var openModals = document.querySelectorAll('.keyword-popup-modal[style*=\"display: block\"]');
                    openModals.forEach(function(modal) {
                        modal.style.display = 'none';
                        document.body.style.overflow = 'auto';
                    });
                }
            });
        });
        </script>";
        
        echo $js;
    }
}

// Initialize the plugin
new WC_Keyword_Popups();
