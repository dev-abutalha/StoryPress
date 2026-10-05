<?php
/**
 * StoryPress Settings admin page renderer.
 *
 * @package StoryPress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
function storypress_options_page(){if(!current_user_can('manage_options'))return;$o=wp_parse_args(get_option('storypress_options',array()),storypress_option_defaults());$logo_id=absint(get_theme_mod('custom_logo'));$site_icon=absint(get_option('site_icon'));$tabs=array('general'=>__('General','storypress'),'home'=>__('Home page','storypress'),'blog'=>__('Blog','storypress'),'header'=>__('Header','storypress'),'footer'=>__('Footer','storypress'),'code'=>__('Code','storypress'),'security'=>__('Security','storypress'),'performance'=>__('Performance','storypress'));$socials=storypress_social_networks();?>
<div class="wrap storypress-settings">
<header class="storypress-settings__header">
<div class="storypress-settings__brand">
<img class="storypress-settings__icon" src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/StoryPressIcon.png' ); ?>" alt="" width="52" height="52">
<div class="storypress-settings__title">
<p>
<?php esc_html_e('StoryPress / Theme workspace','storypress'); ?>
</p>
<h1>
<?php esc_html_e('Make the publication yours.','storypress'); ?>
</h1>
<span>
<?php esc_html_e('A focused control center for your brand, layouts, and reading experience.','storypress'); ?>
</span>
</div>
</div>
<a class="storypress-preview" href="<?php echo esc_url(home_url('/')); ?>" target="_blank" rel="noopener noreferrer">
<?php esc_html_e('View site ↗','storypress'); ?>
</a>
</header>
<?php settings_errors(); ?>
<form action="options.php" method="post" class="storypress-settings__form">
<?php settings_fields('storypress_options_group'); ?>
<nav class="storypress-tabs" role="tablist" aria-label="<?php esc_attr_e('Theme settings sections','storypress'); ?>">
<?php foreach($tabs as $id=>$label): ?>
<button type="button" role="tab" id="tab-<?php echo esc_attr($id); ?>" aria-controls="panel-<?php echo esc_attr($id); ?>" aria-selected="<?php echo 'general'===$id?'true':'false'; ?>" data-storypress-tab="<?php echo esc_attr($id); ?>">
<?php echo esc_html($label); ?>
</button>
<?php endforeach; ?>
</nav>
<div class="storypress-settings__panels">
<section id="panel-general" class="storypress-panel" role="tabpanel" aria-labelledby="tab-general" data-storypress-panel="general">
<p class="eyebrow">
<?php esc_html_e('Foundation','storypress'); ?>
</p>
<h2>
<?php esc_html_e('Brand & appearance','storypress'); ?>
</h2>
<p class="panel-lede">
<?php esc_html_e('Establish the visual foundation without touching code.','storypress'); ?>
</p>
		<nav class="storypress-subtabs" role="tablist" aria-label="<?php esc_attr_e('General settings sections','storypress'); ?>" data-storypress-subtab-group="general">
			<button type="button" role="tab" id="subtab-general-identity" aria-controls="subpanel-general-identity" aria-selected="true" data-storypress-subtab="identity" data-storypress-subtab-group="general"><?php esc_html_e('Identity','storypress'); ?></button>
			<button type="button" role="tab" id="subtab-general-branding" aria-controls="subpanel-general-branding" aria-selected="false" tabindex="-1" data-storypress-subtab="branding" data-storypress-subtab-group="general"><?php esc_html_e('Branding','storypress'); ?></button>
			<button type="button" role="tab" id="subtab-general-social" aria-controls="subpanel-general-social" aria-selected="false" tabindex="-1" data-storypress-subtab="social" data-storypress-subtab-group="general"><?php esc_html_e('Social links','storypress'); ?></button>
			<button type="button" role="tab" id="subtab-general-preferences" aria-controls="subpanel-general-preferences" aria-selected="false" tabindex="-1" data-storypress-subtab="preferences" data-storypress-subtab-group="general"><?php esc_html_e('Preferences','storypress'); ?></button>
		</nav>
		<div id="subpanel-general-identity" class="storypress-subpanel" role="tabpanel" aria-labelledby="subtab-general-identity" data-storypress-subpanel="identity" data-storypress-subtab-group="general">
<div class="storypress-card">
<div class="storypress-identity">
<div class="storypress-identity__heading"><strong><?php esc_html_e('Site identity','storypress'); ?></strong><p><?php esc_html_e('These controls update WordPress’s native site title and tagline settings.','storypress'); ?></p></div>
<div class="storypress-identity__fields"><label for="storypress-site-title"><span><?php esc_html_e('Site title','storypress'); ?></span><input id="storypress-site-title" type="text" name="storypress_options[site_title]" value="<?php echo esc_attr( get_option( 'blogname' ) ); ?>"></label><label for="storypress-site-tagline"><span><?php esc_html_e('Tagline','storypress'); ?></span><input id="storypress-site-tagline" type="text" name="storypress_options[site_tagline]" value="<?php echo esc_attr( get_option( 'blogdescription' ) ); ?>"></label></div>
</div>
<div class="storypress-control">
<div>
<label for="storypress-primary-color">
<strong>
<?php esc_html_e('Accent color — light mode','storypress'); ?>
</strong>
</label>
<p>
<?php esc_html_e('Used for links, buttons, and editorial highlights when the site is viewed in light mode.','storypress'); ?>
</p>
</div>
<input id="storypress-primary-color" type="color" name="storypress_options[primary_color]" value="<?php echo esc_attr(storypress_normalize_hex($o['primary_color'])); ?>">
</div>
<div class="storypress-control">
<div>
<label for="storypress-primary-color-dark">
<strong>
<?php esc_html_e('Accent color — dark mode','storypress'); ?>
</strong>
</label>
<p>
<?php esc_html_e('Used for links, buttons, and editorial highlights when the site is viewed in dark mode.','storypress'); ?>
</p>
</div>
<input id="storypress-primary-color-dark" type="color" name="storypress_options[primary_color_dark]" value="<?php echo esc_attr(storypress_normalize_hex($o['primary_color_dark'])); ?>">
</div>
</div>
	</div>
	<div id="subpanel-general-branding" class="storypress-subpanel" role="tabpanel" aria-labelledby="subtab-general-branding" data-storypress-subpanel="branding" data-storypress-subtab-group="general" hidden>
<div class="storypress-card storypress-card--logo">
<div class="storypress-card__head"><strong><?php esc_html_e('Logos & favicon','storypress'); ?></strong><p><?php esc_html_e('Add the pair side by side — they swap automatically in dark mode. Set the favicon and display width below.','storypress'); ?></p></div>
<?php storypress_option_logo('custom_logo',__('Logo (light mode)','storypress'),__('Shown by default in the header and footer.','storypress'),$logo_id);storypress_option_logo('dark_logo',__('Logo (dark mode)','storypress'),__('Replaces the main logo automatically in dark mode.','storypress'),$o['dark_logo']);storypress_option_number('logo_width',__('Logo width','storypress'),__('Display size in pixels for both logos (40 – 400 px).','storypress'),$o,40,400,5);storypress_option_logo('site_icon',__('Favicon','storypress'),__('The small icon shown in browser tabs and bookmarks.','storypress'),$site_icon); ?>
</div>
<div class="storypress-card storypress-card--home">
<div class="storypress-card__head"><strong><?php esc_html_e('Home page','storypress'); ?></strong><p><?php esc_html_e('Pick which page the site opens on. StoryPress applies the Home template automatically.','storypress'); ?></p></div>
<?php storypress_option_select('home_page',__('Front page','storypress'),__('Select any page from your site. It is set as the homepage and switched to the Home template automatically.','storypress'),storypress_page_choices(),$o); ?>
</div>
	</div>
	<div id="subpanel-general-social" class="storypress-subpanel" role="tabpanel" aria-labelledby="subtab-general-social" data-storypress-subpanel="social" data-storypress-subtab-group="general" hidden>
<div class="storypress-card">
<div class="storypress-card__head"><strong><?php esc_html_e('Social links','storypress'); ?></strong><p><?php esc_html_e('Add your profiles — they appear in the footer and on the About and Contact pages.','storypress'); ?></p></div>
<?php foreach($socials as $key=>$net):storypress_option_text('social_'.$key,$net['label'],sprintf(__('Paste the full URL for your %s profile.','storypress'),'<strong>'.esc_html($net['label']).'</strong>'),$o,'url',$net['placeholder']);endforeach; ?>
</div>
	</div>
	<div id="subpanel-general-preferences" class="storypress-subpanel" role="tabpanel" aria-labelledby="subtab-general-preferences" data-storypress-subpanel="preferences" data-storypress-subtab-group="general" hidden>
<div class="storypress-card">
<?php storypress_option_select('dark_mode',__('Dark mode','storypress'),__('Choose how readers experience the darker color palette.', 'storypress'),array('disabled'=>__('Disabled','storypress'),'system'=>__('Use device preference','storypress'),'manual'=>__('Show manual toggle','storypress')),$o);storypress_option_select('container_width',__('Content width','storypress'),__('Set the maximum width for broad editorial layouts.','storypress'),array('1080'=>__('Compact — 1080px','storypress'),'1180'=>__('Standard — 1180px','storypress'),'1320'=>__('Wide — 1320px','storypress')),$o); ?>
</div>
 </div>
</section>
<section id="panel-blog" class="storypress-panel" role="tabpanel" aria-labelledby="tab-blog" data-storypress-panel="blog" hidden>
<p class="eyebrow"><?php esc_html_e('Blog archive','storypress'); ?></p><h2><?php esc_html_e('Blog page layout','storypress'); ?></h2><p class="panel-lede"><?php esc_html_e('Control the presentation of your posts page, category archives, tags, and search results. This also drives your front page when it shows your latest posts.','storypress'); ?></p>
<div class="storypress-card"><?php storypress_option_visual('blog_layout',__('Archive layout','storypress'),__('Choose how your blog index, categories, tags, and search results present stories.','storypress'),storypress_blog_layout_choices(),storypress_get_option('blog_layout'));storypress_option_visual('single_layout',__('Single article layout','storypress'),__('Choose the default structure for individual blog posts.','storypress'),storypress_single_layout_choices(),storypress_get_option('single_layout'));storypress_option_number('blog_posts_per_page',__('Posts per page','storypress'),__('Choose how many posts appear on each Blog archive page.','storypress'),array('blog_posts_per_page'=>get_option('posts_per_page',storypress_get_option('blog_posts_per_page'))),1,24,1); ?>
<div class="storypress-toggle-grid"><div class="storypress-control storypress-control--full"><div><strong><?php esc_html_e('Sidebar','storypress'); ?></strong><p><?php esc_html_e('Show the Sidebar widget area beside the post feed on blog, category, tag, search, and author pages.','storypress'); ?></p></div></div><?php storypress_option_checkbox('archive_sidebar',__('Show sidebar on archives','storypress'),__('Display the Sidebar widgets next to the post feed.','storypress')); ?></div>
<div class="storypress-control storypress-control--full"><div><strong><?php esc_html_e('Reading experience','storypress'); ?></strong><p><?php esc_html_e('Choose the tools that help readers move through long articles.','storypress'); ?></p></div></div>
<div class="storypress-toggle-grid"><?php storypress_option_checkbox('show_breadcrumbs',__('Breadcrumbs','storypress'),__('Show a clear path above single articles.','storypress'));storypress_option_checkbox('reading_progress',__('Reading progress','storypress'),__('Show a thin progress indicator at the top of articles.','storypress'));storypress_option_checkbox('table_of_contents',__('Table of contents','storypress'),__('Build a lightweight guide from H2 and H3 headings.','storypress'));storypress_option_checkbox('share_links',__('Share links','storypress'),__('Show native sharing URLs and a copy-link action.','storypress'));storypress_option_checkbox('related_posts',__('Related posts','storypress'),__('Show up to three posts from matching categories.','storypress'));storypress_option_checkbox('show_author',__('Author name','storypress'),__('Show the author alongside article metadata.','storypress'));storypress_option_checkbox('author_box',__('Author box','storypress'),__('Show a short "About the author" card under each article.','storypress'));storypress_option_checkbox('show_date',__('Published date','storypress'),__('Show the publication date in article metadata.','storypress'));storypress_option_checkbox('show_reading_time',__('Reading time','storypress'),__('Show the estimated reading length.','storypress')); ?></div></div>
</section>
<section id="panel-header" class="storypress-panel" role="tabpanel" aria-labelledby="tab-header" data-storypress-panel="header" hidden>
<p class="eyebrow">
<?php esc_html_e('Navigation','storypress'); ?>
</p>
<h2>
<?php esc_html_e('Header controls','storypress'); ?>
</h2>
<p class="panel-lede">
<?php esc_html_e('Keep navigation compact and purposeful.','storypress'); ?>
</p>
<div class="storypress-card">
<?php storypress_option_select('header_menu',__('Header menu','storypress'),__('Choose which WordPress menu appears in the main header.','storypress'),storypress_menu_choices(),$o);storypress_option_checkbox('header_sticky',__('Sticky header','storypress'),__('Keep compact navigation available as readers scroll.','storypress'));storypress_option_checkbox('show_search',__('Show search','storypress'),__('Display the lightweight, accessible site search button.','storypress'));storypress_option_checkbox('cta_enabled',__('Show call-to-action button','storypress'),__('Add one restrained action beside the menu.','storypress')); ?>
<div class="storypress-control">
<div>
<label for="storypress-cta-text">
<strong>
<?php esc_html_e('Button text','storypress'); ?>
</strong>
</label>
<p>
<?php esc_html_e('For example: Subscribe or Start Here.','storypress'); ?>
</p>
</div>
<input id="storypress-cta-text" type="text" name="storypress_options[cta_text]" value="<?php echo esc_attr($o['cta_text']); ?>">
</div>
<div class="storypress-control">
<div>
<label for="storypress-cta-url">
<strong>
<?php esc_html_e('Button URL','storypress'); ?>
</strong>
</label>
<p>
<?php esc_html_e('Use a full URL or a path on this site.','storypress'); ?>
</p>
</div>
<input id="storypress-cta-url" type="url" name="storypress_options[cta_url]" value="<?php echo esc_attr($o['cta_url']); ?>">
</div>
</div>
</section>
<section id="panel-footer" class="storypress-panel" role="tabpanel" aria-labelledby="tab-footer" data-storypress-panel="footer" hidden>
<p class="eyebrow">
<?php esc_html_e('Closing details','storypress'); ?>
</p>
<h2>
<?php esc_html_e('Footer controls','storypress'); ?>
</h2>
<p class="panel-lede">
<?php esc_html_e('Complete the site without adding visual clutter.','storypress'); ?>
</p>
<div class="storypress-card">
<?php storypress_option_text('footer_text',__('Footer about text','storypress'),__('A short description shown under the logo in the footer. Leave blank to hide it.','storypress'),$o);storypress_option_select('footer_menu',__('Footer menu','storypress'),__('Choose the main navigation column shown next to the logo.','storypress'),storypress_menu_choices(),$o);storypress_option_select('legal_menu',__('Legal menu','storypress'),__('Choose a second small menu for Privacy Policy & Terms. It appears as a single row to the right of the copyright line.','storypress'),storypress_menu_choices(),$o); ?>
<div class="storypress-control">
<div>
<label for="storypress-copyright">
<strong>
<?php esc_html_e('Copyright line','storypress'); ?>
</strong>
</label>
<p>
<?php esc_html_e('Leave blank to use the site name and current year.','storypress'); ?>
</p>
</div>
<input id="storypress-copyright" type="text" name="storypress_options[copyright]" value="<?php echo esc_attr($o['copyright']); ?>">
</div>
<?php storypress_option_checkbox('back_to_top',__('Back-to-top button','storypress'),__('Give long pages a small, convenient return control.','storypress')); ?>
</div>
</section>
<section id="panel-home" class="storypress-panel" role="tabpanel" aria-labelledby="tab-home" data-storypress-panel="home" hidden>
<p class="eyebrow">
<?php esc_html_e('Front page','storypress'); ?>
</p>
<h2>
<?php esc_html_e('Home page sections','storypress'); ?>
</h2>
<p class="panel-lede">
<?php esc_html_e('Build your homepage section by section. Every section is optional and fully controlled here — no page template needed.','storypress'); ?>
</p>
<div class="storypress-card">
<div class="storypress-card__head"><strong><?php esc_html_e('Homepage design','storypress'); ?></strong><p><?php esc_html_e('Choose a visual layout. Both designs use the same homepage content and section settings.','storypress'); ?></p></div>
<?php storypress_option_select('home_design',__('Design','storypress'),__('Switch between the original layout and the minimal editorial layout.','storypress'),array('classic'=>__('Classic','storypress'),'studio'=>__('Editorial studio','storypress')),$o); ?>
</div>
<?php $dests=storypress_category_choices();$dests[0]=__('Use top-level categories (no parent)','storypress'); ?>
<nav class="storypress-subtabs" role="tablist" aria-label="<?php esc_attr_e('Home page sections','storypress'); ?>" data-storypress-subtab-group="home">
<button type="button" role="tab" id="subtab-hero" aria-controls="subpanel-hero" aria-selected="true" data-storypress-subtab="hero" data-storypress-subtab-group="home"><?php esc_html_e('Hero','storypress'); ?></button>
<button type="button" role="tab" id="subtab-destinations" aria-controls="subpanel-destinations" aria-selected="false" tabindex="-1" data-storypress-subtab="destinations" data-storypress-subtab-group="home"><?php esc_html_e('Destinations','storypress'); ?></button>
<button type="button" role="tab" id="subtab-content" aria-controls="subpanel-content" aria-selected="false" tabindex="-1" data-storypress-subtab="content" data-storypress-subtab-group="home"><?php esc_html_e('Main content','storypress'); ?></button>
<button type="button" role="tab" id="subtab-sidebar" aria-controls="subpanel-sidebar" aria-selected="false" tabindex="-1" data-storypress-subtab="sidebar" data-storypress-subtab-group="home"><?php esc_html_e('Sidebar','storypress'); ?></button>
<button type="button" role="tab" id="subtab-feed" aria-controls="subpanel-feed" aria-selected="false" tabindex="-1" data-storypress-subtab="feed" data-storypress-subtab-group="home"><?php esc_html_e('Latest posts','storypress'); ?></button>
</nav>
<div id="subpanel-hero" class="storypress-subpanel" role="tabpanel" aria-labelledby="subtab-hero" data-storypress-subpanel="hero" data-storypress-subtab-group="home">
<div class="storypress-card">
<div class="storypress-card__head"><strong><?php esc_html_e('Hero','storypress'); ?></strong><p><?php esc_html_e('The welcome message readers see at the very top.','storypress'); ?></p></div>
<?php storypress_option_text('home_hero_eyebrow',__('Eyebrow','storypress'),__('A short label shown above the headline.','storypress'),$o);storypress_option_text('home_hero_title',__('Headline','storypress'),__('The main hero heading.','storypress'),$o);storypress_option_textarea('home_hero_text',__('Intro paragraph','storypress'),__('One or two sentences of context below the heading.','storypress'),$o); ?>
<div class="storypress-control">
<div>
<label for="storypress-home-hero-cta-text"><strong><?php esc_html_e('Call-to-action text','storypress'); ?></strong></label>
<p><?php esc_html_e('The hero button label.','storypress'); ?></p>
</div>
<input id="storypress-home-hero-cta-text" type="text" name="storypress_options[home_hero_cta_text]" value="<?php echo esc_attr($o['home_hero_cta_text']); ?>">
</div>
<div class="storypress-control">
<div>
<label for="storypress-home-hero-cta-url"><strong><?php esc_html_e('Call-to-action URL','storypress'); ?></strong></label>
<p><?php esc_html_e('Leave blank to point at your latest posts.','storypress'); ?></p>
</div>
<input id="storypress-home-hero-cta-url" type="url" name="storypress_options[home_hero_cta_url]" value="<?php echo esc_attr($o['home_hero_cta_url']); ?>">
</div>
</div>
</div>
<div id="subpanel-destinations" class="storypress-subpanel" role="tabpanel" aria-labelledby="subtab-destinations" data-storypress-subpanel="destinations" data-storypress-subtab-group="home" hidden>
<div class="storypress-card">
<div class="storypress-card__head"><strong><?php esc_html_e('Travel destinations','storypress'); ?></strong><p><?php esc_html_e('Showcase categories as destination cards. Pick one parent category — only its direct children are shown as cards.','storypress'); ?></p></div>
<?php storypress_option_checkbox('home_destinations_enabled',__('Show destinations','storypress'),__('Display the destination mosaic below the hero.','storypress'));storypress_option_select('home_destinations_cat',__('Parent category','storypress'),__('Cards are built from the direct children of this category. Build a category such as "Destinations" and add regions below it.','storypress'),$dests,$o);storypress_option_text('home_destinations_title',__('Heading','storypress'),__('Leave blank to hide the heading.','storypress'),$o);storypress_option_number('home_destinations_count',__('Number of destination cards','storypress'),__('Between 1 and 12.','storypress'),$o,1,12,1); ?>
</div>
</div>
<div id="subpanel-content" class="storypress-subpanel" role="tabpanel" aria-labelledby="subtab-content" data-storypress-subpanel="content" data-storypress-subtab-group="home" hidden>
<?php storypress_section_options('latest',__('Latest stories','storypress'),__('The newest writing, right below the destinations.','storypress'),$o,true);storypress_section_options('feature',__('Featured story','storypress'),__('A single, spotlight story with a full-width card.','storypress'),$o); ?>
</div>
<div id="subpanel-sidebar" class="storypress-subpanel" role="tabpanel" aria-labelledby="subtab-sidebar" data-storypress-subpanel="sidebar" data-storypress-subtab-group="home" hidden>
<?php storypress_section_options('popular',__('Popular posts','storypress'),__('The most-discussed stories in the sidebar.','storypress'),$o);storypress_section_options('editors',__("Editor's choice",'storypress'),__('Handpicked highlights in the sidebar.','storypress'),$o); ?>
</div>
<div id="subpanel-feed" class="storypress-subpanel" role="tabpanel" aria-labelledby="subtab-feed" data-storypress-subpanel="feed" data-storypress-subtab-group="home" hidden>
<?php storypress_section_options('latestposts',__('Latest posts','storypress'),__('The closing feed with all posts.','storypress'),$o,true,true); ?>
</div>
</section>
<section id="panel-code" class="storypress-panel" role="tabpanel" aria-labelledby="tab-code" data-storypress-panel="code" hidden>
<p class="eyebrow">
<?php esc_html_e('Integrations','storypress'); ?>
</p>
<h2>
<?php esc_html_e('Custom code & privacy','storypress'); ?>
</h2>
<p class="panel-lede">
<?php esc_html_e('Drop in tracking, analytics, or verification snippets without a single plugin.','storypress'); ?>
</p>
<div class="storypress-card">
<div class="storypress-card__head"><strong><?php esc_html_e('Code injection','storypress'); ?></strong><p><?php esc_html_e('Paste full scripts per placement; they apply to every page. For Google Tag Manager, put the tracking snippet under Head and its noscript fallback under Footer.','storypress'); ?></p></div>
<?php storypress_option_textarea('code_head',__('Head','storypress'),__('Injected near the top of <head> — ideal for Tag Manager, analytics, and search-console verification.','storypress'),$o,8);storypress_option_textarea('code_body',__('Body — after opening tag','storypress'),__('Injected immediately after <body> opens.','storypress'),$o,4);storypress_option_textarea('code_footer',__('Footer','storypress'),__('Injected at the very end of <body> — ideal for no-JavaScript fallbacks.','storypress'),$o,8); ?>
</div>
<div class="storypress-card">
<div class="storypress-card__head"><strong><?php esc_html_e('Privacy','storypress'); ?></strong><p><?php esc_html_e('The consent banner shows once to first-time visitors and stores their choice in a cookie named storypress_consent. It does not block injected scripts by itself — gate analytics through Google Consent Mode.','storypress'); ?></p></div>
<?php storypress_option_checkbox('consent_banner',__('Show consent banner','storypress'),__('Ask first-time visitors to accept cookies before continuing.','storypress'));storypress_option_textarea('consent_message',__('Banner message','storypress'),__('Leave blank to use the default text.','storypress'),$o,3); ?>
</div>
</section>
<section id="panel-security" class="storypress-panel" role="tabpanel" aria-labelledby="tab-security" data-storypress-panel="security" hidden>
<p class="eyebrow">
<?php esc_html_e('Defense','storypress'); ?>
</p>
<h2>
<?php esc_html_e('Security','storypress'); ?>
</h2>
<p class="panel-lede">
<?php esc_html_e('Tighten remote access to your site. These are the two most exposed endpoints on any WordPress install.','storypress'); ?>
</p>
<div class="storypress-card">
<?php storypress_option_select('rest_api',__('REST API','storypress'),__('Block scripted access to the JSON API used by spammers and scanners. "Log-in required" keeps block-editor, themes, and plugins fully functional. "Disabled" prints a 403 for every REST request, including customizers and some admin tools.','storypress'),array('enabled'=>__('Enabled (default)','storypress'),'logged_in'=>__('Log-in required','storypress'),'disabled'=>__('Disabled','storypress')),$o);storypress_option_checkbox('disable_xmlrpc',__('Disable XML-RPC','storypress'),__('Blocks xmlrpc.php pingback floods and password brute-force attempts.','storypress')); ?>
</div>
<div class="storypress-card">
<?php storypress_option_checkbox('show_comments',__('Show comments','storypress'),__('The master control. Turning it off closes comment forms, REST comment routes, pingbacks, feeds, and new-post default status — everywhere at once, not just on the page.','storypress')); ?>
</div>
</section>
<section id="panel-performance" class="storypress-panel" role="tabpanel" aria-labelledby="tab-performance" data-storypress-panel="performance" hidden>
<p class="eyebrow">
<?php esc_html_e('Speed','storypress'); ?>
</p>
<h2>
<?php esc_html_e('Performance','storypress'); ?>
</h2>
<p class="panel-lede">
<?php esc_html_e('Trim a few default WordPress extras the moment they start getting in the way.','storypress'); ?>
</p>
<div class="storypress-card">
<div class="storypress-card__head"><strong><?php esc_html_e('Unused features','storypress'); ?></strong><p><?php esc_html_e('Everything here is safe to disable and saves a little work for the browser.','storypress'); ?></p></div>
<div class="storypress-toggle-grid"><?php storypress_option_checkbox('perf_remove_emoji',__('Remove emoji scripts','storypress'),__('Drops the emoji style and script WordPress loads in every page.','storypress'));storypress_option_checkbox('perf_disable_trackbacks',__('Disable pingbacks & trackbacks','storypress'),__('Stops cross-site ping traffic and removes the X-Pingback response header.','storypress'));storypress_option_checkbox('perf_disable_embeds',__('Disable oEmbed embeds','storypress'),__('Removes embed discovery links, scripts, and the oEmbed REST route.','storypress')); ?></div>
</div>
</section>
</div>
<footer class="storypress-savebar">
<span>
<?php esc_html_e('Changes are saved safely using WordPress settings.','storypress'); ?>
</span>
<input type="hidden" name="submit" value="<?php esc_attr_e('Save changes','storypress'); ?>">
<button id="submit" type="submit" class="button button-primary button-large storypress-save-button" data-saving-label="<?php esc_attr_e('Saving...','storypress'); ?>">
<span class="storypress-save-button__label"><?php esc_html_e('Save changes','storypress'); ?></span>
<span class="storypress-save-spinner" aria-hidden="true" hidden></span>
</button>
</footer>
</form>
</div>
<?php }