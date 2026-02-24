# DVC Internship Assessment - Mohammed Abrar

## Contact Information
- **Name:** Mohammed Abrar
- **Email:** mohammedabrar7416@gmail.com
- **Contact No:** +91 8328367416
- **GitHub:** [Abrar-khan04](https://github.com/Abrar-khan04)

---

## Question 1: Responsive Product Card Component
**Approach:**  
Implemented a mobile-first, semantic HTML5 component. Used Vanilla CSS with Flexbox/Grid for layout and CSS variables for a consistent "premium" look (Glassmorphism inspired). JavaScript handles the quantity state (1-10) and success notification logic.

**Assumptions:**
- Standard modern browser support is sufficient.
- Fallback image should be a clear placeholder if the primary URL fails.

**Files:** [Question 1/product_card.html](./Question%201/product_card.html)  
**Estimated Time:** 1 Hour

---

## Question 2: WordPress Custom Functionality
**Approach:**  
Developed a standalone WordPress plugin. Registered a Custom Post Type (CPT) for 'Testimonials' with Gutenberg support. Built a custom meta box for client details with nonce verification for security. Created a shortcode `[testimonials]` that renders a responsive JS-based slider.

**Assumptions:**
- The plugin will be used on a site running WordPress 5.0+.
- No external libraries (like Slick or Swiper) should be used, so I built a custom vanilla JS slider.

**Files:** [Question 2/testimonials-manager.php](./Question%202/testimonials-manager.php)  
**Estimated Time:** 1.5 Hours

---

## Question 3: Weather Dashboard (API Integration)
**Approach:**  
Built a vanilla JS application using the OpenWeatherMap API with `async/await`. Implemented robust error handling (city not found, network issues). Used `localStorage` to cache the last searched city for persistence. The UI is responsive with high-end CSS gradients.

**Assumptions:**
- User will provide a valid OpenWeatherMap API key (already updated in the file).
- 5-Day forecast uses the mid-day (12:00 PM) data points from the API for consistency.

**Files:** [Question 3/weather_dashboard.html](./Question%203/weather_dashboard.html)  
**Estimated Time:** 2 Hours

---

## Live Demo & Running Instructions
1. **Product Card & Weather Dashboard**: Simply open the respective `.html` files in any browser.
2. **WordPress Plugin**: Upload the `.php` file to a WordPress site's `wp-content/plugins` folder and activate it.

## Total Estimated Time: 4.5 Hours
