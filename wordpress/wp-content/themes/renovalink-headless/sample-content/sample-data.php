<?php
/**
 * Sample Content for RenovaLink
 * Run this file to populate the site with sample data
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Create Sample Content Function
 */
function create_renovalink_sample_content() {
    
    // Check if we already have sample content
    $existing_services = get_posts(array('post_type' => 'servicios', 'posts_per_page' => 1));
    if (!empty($existing_services)) {
        return; // Sample content already exists
    }
    
    // Services Sample Data
    $services_data = array(
        array(
            'title' => 'Pool Remodeling & Design',
            'content' => 'Transform your backyard oasis with our comprehensive pool remodeling services. From complete renovations to design upgrades, we specialize in creating stunning pool environments that enhance your property value and lifestyle.

Our expert team handles everything from structural repairs to aesthetic improvements, including new tile work, lighting systems, waterfalls, and modern filtration systems. We work with all pool types and can transform dated pools into contemporary masterpieces.',
            'excerpt' => 'Complete pool renovations, design upgrades, lighting, waterfalls, and modern filtration systems for your backyard oasis.',
            'service_icon' => '', // Will be set after image upload
            'short_description' => 'Transform your backyard with stunning pool renovations, modern lighting, waterfalls, and complete design upgrades.',
            'service_features' => array(
                array('feature_text' => 'Complete pool renovations'),
                array('feature_text' => 'Custom lighting systems'),
                array('feature_text' => 'Waterfall installations'),
                array('feature_text' => 'Modern filtration upgrades'),
                array('feature_text' => 'Tile and surface restoration'),
                array('feature_text' => 'Pool deck remodeling')
            ),
            'cta_text' => 'Get Pool Renovation Quote',
            'cta_link' => '#contact',
            'price_range' => 'Starting at $5,000',
            'service_duration' => '1-3 weeks',
            'warranty_info' => '2-year warranty on all work'
        ),
        array(
            'title' => 'Concrete & Flooring Services',
            'content' => 'Professional concrete and flooring solutions for residential and commercial properties. Our certified team provides comprehensive services from repairs and leveling to decorative finishes and complete installations.

We specialize in concrete repair, foundation work, decorative concrete, polished floors, and demolition services. Our advanced techniques ensure durable, long-lasting results that meet Florida building codes and withstand the local climate.',
            'excerpt' => 'Professional concrete repairs, leveling, decorative finishes, and flooring solutions with certified expertise.',
            'service_icon' => '',
            'short_description' => 'Expert concrete repairs, leveling, decorative finishes, and complete flooring solutions for lasting results.',
            'service_features' => array(
                array('feature_text' => 'Concrete crack repairs'),
                array('feature_text' => 'Foundation leveling'),
                array('feature_text' => 'Decorative concrete finishes'),
                array('feature_text' => 'Polished concrete floors'),
                array('feature_text' => 'Safe demolition services'),
                array('feature_text' => 'Commercial grade materials')
            ),
            'cta_text' => 'Get Concrete Quote',
            'cta_link' => '#contact',
            'price_range' => 'Starting at $800',
            'service_duration' => '2-5 days',
            'warranty_info' => '5-year structural warranty'
        ),
        array(
            'title' => 'Residential Cleaning Services',
            'content' => 'Comprehensive residential cleaning services designed to keep your home pristine. From deep cleaning to regular maintenance, we provide eco-friendly solutions that ensure a healthy, spotless living environment.

Our professional cleaning team offers flexible scheduling, custom cleaning plans, and uses only environmentally safe products. We handle everything from move-in/move-out cleaning to ongoing weekly or monthly maintenance.',
            'excerpt' => 'Professional residential cleaning with eco-friendly products, flexible scheduling, and custom cleaning plans.',
            'service_icon' => '',
            'short_description' => 'Professional residential cleaning with eco-friendly products and flexible scheduling for spotless homes.',
            'service_features' => array(
                array('feature_text' => 'Deep cleaning services'),
                array('feature_text' => 'Recurring maintenance'),
                array('feature_text' => 'Move-in/out cleaning'),
                array('feature_text' => 'Eco-friendly products'),
                array('feature_text' => 'Flexible scheduling'),
                array('feature_text' => 'Satisfaction guarantee')
            ),
            'cta_text' => 'Schedule Cleaning',
            'cta_link' => '#contact',
            'price_range' => 'Starting at $120',
            'service_duration' => '2-6 hours',
            'warranty_info' => 'Satisfaction guarantee'
        ),
        array(
            'title' => 'Technical Support & Structural Plans',
            'content' => 'Professional structural engineering and technical support services by a certified Florida engineer. We provide comprehensive structural analysis, engineering plans, and technical consultation for residential and commercial projects.

Our certified engineer offers steel detailing, structural assessments, building code compliance, and professional engineering stamps for permits. We ensure all projects meet or exceed Florida building standards and safety requirements.',
            'excerpt' => 'Certified Florida engineer providing structural plans, analysis, and technical support for building projects.',
            'service_icon' => '',
            'short_description' => 'Certified Florida engineer providing structural analysis, building plans, and technical consultation services.',
            'service_features' => array(
                array('feature_text' => 'Certified FL Engineer'),
                array('feature_text' => 'Structural analysis'),
                array('feature_text' => 'Steel detailing'),
                array('feature_text' => 'Building code compliance'),
                array('feature_text' => 'Engineering stamps'),
                array('feature_text' => 'Technical consultation')
            ),
            'cta_text' => 'Consult Engineer',
            'cta_link' => '#contact',
            'price_range' => 'Starting at $500',
            'service_duration' => '3-10 business days',
            'warranty_info' => 'Professional liability insured'
        )
    );
    
    // Create Services
    foreach ($services_data as $service_data) {
        $service_id = wp_insert_post(array(
            'post_title' => $service_data['title'],
            'post_content' => $service_data['content'],
            'post_excerpt' => $service_data['excerpt'],
            'post_type' => 'servicios',
            'post_status' => 'publish',
            'meta_input' => array(
                'short_description' => $service_data['short_description'],
                'service_features' => $service_data['service_features'],
                'cta_text' => $service_data['cta_text'],
                'cta_link' => $service_data['cta_link'],
                'price_range' => $service_data['price_range'],
                'service_duration' => $service_data['service_duration'],
                'warranty_info' => $service_data['warranty_info']
            )
        ));
    }
    
    // Projects Sample Data
    $projects_data = array(
        array(
            'title' => 'Luxury Pool Renovation - Miami Beach',
            'content' => 'Complete transformation of a 1980s pool into a modern luxury oasis. This project included new LED lighting system, glass tile refinishing, waterfall feature, and upgraded filtration system.',
            'excerpt' => 'Complete luxury pool transformation with LED lighting and waterfall features.',
            'project_type' => 'pool',
            'project_location' => 'Miami Beach, FL',
            'project_duration' => '3 weeks',
            'project_size' => '800 sq ft pool area',
            'client_testimonial' => 'The team transformed our old pool into a stunning centerpiece. The new lighting and waterfall exceeded our expectations!',
            'project_challenges' => 'Working around existing landscaping and integrating new electrical systems with vintage pool infrastructure.'
        ),
        array(
            'title' => 'Concrete Driveway Restoration - Fort Lauderdale',
            'content' => 'Complete restoration of a cracked and uneven concrete driveway. Project included crack repair, leveling, and decorative stamped concrete finish.',
            'excerpt' => 'Driveway restoration with decorative stamped concrete finish.',
            'project_type' => 'concrete',
            'project_location' => 'Fort Lauderdale, FL',
            'project_duration' => '5 days',
            'project_size' => '1,200 sq ft driveway',
            'client_testimonial' => 'Our driveway looks brand new! The decorative finish adds so much curb appeal to our home.',
            'project_challenges' => 'Matching existing concrete color and ensuring proper drainage in South Florida climate.'
        ),
        array(
            'title' => 'Move-Out Deep Cleaning - Boca Raton',
            'content' => 'Comprehensive move-out cleaning for a 3,500 sq ft home. Services included deep cleaning of all surfaces, carpet cleaning, window cleaning, and sanitization.',
            'excerpt' => 'Complete move-out deep cleaning for large residential home.',
            'project_type' => 'cleaning',
            'project_location' => 'Boca Raton, FL',
            'project_duration' => '1 day',
            'project_size' => '3,500 sq ft home',
            'client_testimonial' => 'They helped us get our full deposit back! The house was spotless and ready for the next tenants.',
            'project_challenges' => 'Removing years of buildup and ensuring all areas met landlord inspection standards.'
        ),
        array(
            'title' => 'Structural Assessment - Orlando',
            'content' => 'Complete structural analysis and engineering plans for a residential addition. Provided certified drawings and PE stamp for permit approval.',
            'excerpt' => 'Structural engineering plans for residential addition project.',
            'project_type' => 'engineering',
            'project_location' => 'Orlando, FL',
            'project_duration' => '1 week',
            'project_size' => '600 sq ft addition',
            'client_testimonial' => 'Professional, thorough, and helped us navigate the permit process smoothly. Worth every penny.',
            'project_challenges' => 'Ensuring new addition integrates properly with existing foundation and meets hurricane code requirements.'
        )
    );
    
    // Create Projects
    foreach ($projects_data as $project_data) {
        $project_id = wp_insert_post(array(
            'post_title' => $project_data['title'],
            'post_content' => $project_data['content'],
            'post_excerpt' => $project_data['excerpt'],
            'post_type' => 'proyectos',
            'post_status' => 'publish',
            'meta_input' => array(
                'project_type' => $project_data['project_type'],
                'project_location' => $project_data['project_location'],
                'project_duration' => $project_data['project_duration'],
                'project_size' => $project_data['project_size'],
                'client_testimonial' => $project_data['client_testimonial'],
                'project_challenges' => $project_data['project_challenges']
            )
        ));
    }
    
    // Testimonials Sample Data
    $testimonials_data = array(
        array(
            'title' => 'Maria Rodriguez Review',
            'content' => 'RenovaLink completely transformed our backyard pool area. The attention to detail and quality of work exceeded our expectations. The new lighting system creates an amazing ambiance for evening entertaining.',
            'client_name' => 'Maria Rodriguez',
            'client_location' => 'Miami, FL',
            'service_provided' => 'pool',
            'rating' => 5,
            'testimonial_text' => 'RenovaLink completely transformed our backyard pool area. The attention to detail and quality of work exceeded our expectations. The new lighting system creates an amazing ambiance for evening entertaining.',
            'testimonial_date' => date('Y-m-d', strtotime('-2 months')),
            'featured_testimonial' => true
        ),
        array(
            'title' => 'James Mitchell Review',
            'content' => 'Professional concrete work that solved our foundation issues permanently. The team was knowledgeable about Florida building codes and delivered exactly what they promised.',
            'client_name' => 'James Mitchell',
            'client_location' => 'Fort Lauderdale, FL',
            'service_provided' => 'concrete',
            'rating' => 5,
            'testimonial_text' => 'Professional concrete work that solved our foundation issues permanently. The team was knowledgeable about Florida building codes and delivered exactly what they promised.',
            'testimonial_date' => date('Y-m-d', strtotime('-1 month')),
            'featured_testimonial' => true
        ),
        array(
            'title' => 'Sarah Johnson Review',
            'content' => 'Best cleaning service in Boca Raton! They use eco-friendly products and always leave our home spotless. Highly recommend their recurring service.',
            'client_name' => 'Sarah Johnson',
            'client_location' => 'Boca Raton, FL',
            'service_provided' => 'cleaning',
            'rating' => 5,
            'testimonial_text' => 'Best cleaning service in Boca Raton! They use eco-friendly products and always leave our home spotless. Highly recommend their recurring service.',
            'testimonial_date' => date('Y-m-d', strtotime('-3 weeks')),
            'featured_testimonial' => false
        ),
        array(
            'title' => 'Robert Chen Review',
            'content' => 'The structural engineer was incredibly thorough and professional. Made our home addition project go smoothly through permitting. Great communication throughout.',
            'client_name' => 'Robert Chen',
            'client_location' => 'Orlando, FL',
            'service_provided' => 'engineering',
            'rating' => 5,
            'testimonial_text' => 'The structural engineer was incredibly thorough and professional. Made our home addition project go smoothly through permitting. Great communication throughout.',
            'testimonial_date' => date('Y-m-d', strtotime('-6 weeks')),
            'featured_testimonial' => true
        ),
        array(
            'title' => 'Linda Williams Review',
            'content' => 'Excellent pool renovation service. They completed the work on schedule and within budget. Our pool looks amazing and the new filtration system works perfectly.',
            'client_name' => 'Linda Williams',
            'client_location' => 'West Palm Beach, FL',
            'service_provided' => 'pool',
            'rating' => 5,
            'testimonial_text' => 'Excellent pool renovation service. They completed the work on schedule and within budget. Our pool looks amazing and the new filtration system works perfectly.',
            'testimonial_date' => date('Y-m-d', strtotime('-4 weeks')),
            'featured_testimonial' => false
        )
    );
    
    // Create Testimonials
    foreach ($testimonials_data as $testimonial_data) {
        $testimonial_id = wp_insert_post(array(
            'post_title' => $testimonial_data['title'],
            'post_content' => $testimonial_data['content'],
            'post_type' => 'testimonios',
            'post_status' => 'publish',
            'meta_input' => array(
                'client_name' => $testimonial_data['client_name'],
                'client_location' => $testimonial_data['client_location'],
                'service_provided' => $testimonial_data['service_provided'],
                'rating' => $testimonial_data['rating'],
                'testimonial_text' => $testimonial_data['testimonial_text'],
                'testimonial_date' => $testimonial_data['testimonial_date'],
                'featured_testimonial' => $testimonial_data['featured_testimonial']
            )
        ));
    }
    
    // Create service type taxonomy terms
    $service_types = array(
        array('slug' => 'pool', 'name' => 'Pool Remodeling'),
        array('slug' => 'concrete', 'name' => 'Concrete & Flooring'),
        array('slug' => 'cleaning', 'name' => 'Residential Cleaning'),
        array('slug' => 'engineering', 'name' => 'Technical Support & Structural Plans')
    );
    
    foreach ($service_types as $type) {
        if (!term_exists($type['slug'], 'service_type')) {
            wp_insert_term($type['name'], 'service_type', array('slug' => $type['slug']));
        }
    }
    
    // Create location taxonomy terms
    $locations = array('Miami', 'Fort Lauderdale', 'Boca Raton', 'Orlando', 'West Palm Beach', 'Tampa', 'Jacksonville');
    
    foreach ($locations as $location) {
        $slug = sanitize_title($location);
        if (!term_exists($slug, 'project_location')) {
            wp_insert_term($location, 'project_location', array('slug' => $slug));
        }
    }
    
    // Update WordPress options with sample data
    update_option('hero_title', 'Transform Your Home with Professional Remodeling Services');
    update_option('hero_subtitle', 'Quality pool remodeling, concrete work, cleaning services, and structural engineering in Florida');
    update_option('hero_cta_text', 'Get Free Quote Today');
    update_option('hero_cta_link', '#contact');
    update_option('hero_secondary_message', 'Certified Florida Engineer • 15+ Years Experience • Licensed & Insured • $2M Liability Coverage');
    
    update_option('company_name', 'RenovaLink');
    update_option('company_phone', '(555) 123-4567');
    update_option('company_email', 'info@renovalink.com');
    update_option('company_address', '123 Main Street, Miami, FL 33101');
    update_option('company_hours', 'Mon-Fri: 8AM-6PM, Sat: 9AM-4PM, Sun: Emergency Only');
    update_option('service_areas', 'Miami-Dade, Broward, Palm Beach, Orange, Hillsborough, Pinellas');
    
    update_option('facebook_url', 'https://facebook.com/renovalink');
    update_option('instagram_url', 'https://instagram.com/renovalink');
    update_option('linkedin_url', 'https://linkedin.com/company/renovalink');
    
    // Set ACF options if ACF is available
    if (function_exists('update_field')) {
        update_field('company_description', 'RenovaLink is Florida\'s premier remodeling company specializing in pool renovation, concrete work, residential cleaning, and structural engineering services. With over 15 years of experience and a certified Florida engineer on staff, we deliver quality results that exceed expectations.', 'option');
        update_field('years_experience', 15, 'option');
        update_field('projects_completed', 500, 'option');
        update_field('license_number', 'CGC1234567', 'option');
        update_field('insurance_info', '$2M General Liability • Bonded & Insured • Workers Comp Coverage', 'option');
        update_field('hero_overlay_opacity', 50, 'option');
    }
    
    return true;
}

/**
 * Add admin menu item to create sample content
 */
function add_sample_content_admin_menu() {
    add_submenu_page(
        'renovalink-settings',
        'Sample Content',
        'Sample Content',
        'manage_options',
        'renovalink-sample-content',
        'renovalink_sample_content_page'
    );
}
add_action('admin_menu', 'add_sample_content_admin_menu');

function renovalink_sample_content_page() {
    if (isset($_POST['create_sample_content'])) {
        $result = create_renovalink_sample_content();
        if ($result) {
            echo '<div class="notice notice-success"><p>Sample content created successfully!</p></div>';
        } else {
            echo '<div class="notice notice-info"><p>Sample content already exists or there was an issue creating it.</p></div>';
        }
    }
    
    $services_count = wp_count_posts('servicios')->publish;
    $projects_count = wp_count_posts('proyectos')->publish;
    $testimonials_count = wp_count_posts('testimonios')->publish;
    
    ?>
    <div class="wrap">
        <h1>Sample Content for RenovaLink</h1>
        
        <div class="renovalink-quick-stats">
            <div class="renovalink-stat-item">
                <span class="renovalink-stat-number"><?php echo $services_count; ?></span>
                <span class="renovalink-stat-label">Services</span>
            </div>
            <div class="renovalink-stat-item">
                <span class="renovalink-stat-number"><?php echo $projects_count; ?></span>
                <span class="renovalink-stat-label">Projects</span>
            </div>
            <div class="renovalink-stat-item">
                <span class="renovalink-stat-number"><?php echo $testimonials_count; ?></span>
                <span class="renovalink-stat-label">Testimonials</span>
            </div>
        </div>
        
        <?php if ($services_count > 0): ?>
            <div class="notice notice-info">
                <p><strong>Sample content already exists.</strong> You can still run this to add more sample data or update existing content.</p>
            </div>
        <?php endif; ?>
        
        <div class="renovalink-dashboard-widget">
            <h3>Create Sample Content</h3>
            <p>This will create sample services, projects, and testimonials for RenovaLink. This includes:</p>
            <ul>
                <li><strong>4 Services:</strong> Pool Remodeling, Concrete & Flooring, Residential Cleaning, Technical Support</li>
                <li><strong>4 Projects:</strong> One sample project for each service type</li>
                <li><strong>5 Testimonials:</strong> Customer reviews with ratings and photos</li>
                <li><strong>Taxonomy Terms:</strong> Service types and project locations</li>
                <li><strong>Site Settings:</strong> Company information and hero section content</li>
            </ul>
            
            <form method="post">
                <?php submit_button('Create Sample Content', 'primary', 'create_sample_content'); ?>
            </form>
        </div>
        
        <div class="renovalink-dashboard-widget">
            <h3>Next Steps</h3>
            <ol>
                <li>Install and activate <strong>Advanced Custom Fields Pro</strong> plugin</li>
                <li>Upload images for services, projects, and testimonials</li>
                <li>Customize the content to match your specific needs</li>
                <li>Test the REST API endpoints: <code>/wp-json/renovalink/v1/</code></li>
                <li>Connect your Astro.js frontend to the WordPress API</li>
            </ol>
        </div>
    </div>
    <?php
}