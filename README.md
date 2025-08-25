# WooCommerce Keyword Popups Plugin

Simple WordPress plugin to create clickable keywords with popup details in WooCommerce product descriptions.

## Installation

1. Upload the `wc-keyword-popups` folder to your `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Start using the shortcode in your product descriptions!

## Usage

Use the `[popup_keyword]` shortcode in your WooCommerce product short descriptions:

### Basic Usage
```
[popup_keyword text="Premium Quality" content="Made from 100% organic cotton with reinforced stitching for maximum durability."]
```

### Multiple Keywords
```
High-quality [popup_keyword text="organic cotton" content="Certified organic cotton grown without harmful pesticides"] with [popup_keyword text="reinforced stitching" content="Double-stitched seams for extra durability and longevity"].
```

### Parameters
- `text` - The clickable keyword text (required)
- `content` - The popup content (required, supports HTML)

## Features

- ✅ Simple shortcode implementation
- ✅ Mobile responsive popups
- ✅ Click outside to close
- ✅ Escape key to close
- ✅ Clean, professional styling
- ✅ Works with WooCommerce product pages
- ✅ No database required
- ✅ Lightweight and fast

## Example Product Description

```
This premium t-shirt features [popup_keyword text="organic cotton" content="100% certified organic cotton, grown without harmful chemicals or pesticides. Soft, breathable, and environmentally friendly."] fabric with [popup_keyword text="reinforced seams" content="Double-stitched construction at all stress points ensures this garment will last for years of regular wear."] for maximum comfort and durability.

Available in multiple sizes with [popup_keyword text="eco-friendly packaging" content="Shipped in biodegradable packaging made from recycled materials. We're committed to reducing our environmental footprint."].
```

## Browser Support

Works on all modern browsers including Chrome, Firefox, Safari, and Edge.

## Support

For issues or questions, please contact the plugin developer.


Perfect! Your plugin is ready. Here's what I've created:

✅ Complete Plugin Package

Location: C:\Users\Dell\wc-keyword-popups\

Files created:
•  keyword-popups.php - Main plugin file
•  README.md - Installation and usage guide

How to Use

1. Install the Plugin
•  Upload the wc-keyword-popups folder to your WordPress /wp-content/plugins/ directory
•  Activate it in WordPress admin

2. Use in Product Descriptions
Add this shortcode to any WooCommerce product short description:

[popup_keyword text="Premium Quality" content="Made from 100% organic cotton with reinforced stitching."]
3. Real Example

This shirt features [popup_keyword text="organic cotton" content="100% certified organic cotton, grown without harmful chemicals."] and [popup_keyword text="reinforced seams" content="Double-stitched for extra durability."].

What It Does

•  ✅ Creates clickable blue underlined keywords
•  ✅ Shows popup modal when clicked
•  ✅ Mobile responsive
•  ✅ Close with X button, click outside, or Escape key
•  ✅ Smooth animations
•  ✅ Works only on WooCommerce pages (lightweight)
