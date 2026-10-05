<?php
/**
 * StoryPress settings workspace.
 * Split into inc/options/ for maintainability.
 *
 * @package StoryPress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
function storypress_normalize_hex( $color ) {
	$color = trim( (string) $color );
	if ( 1 === preg_match( '/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $color, $matches ) ) {
		$hex = strtolower( $color );
		if ( 3 === strlen( $matches[1] ) ) {
			$hex = '#' . $matches[1][0] . $matches[1][0] . $matches[1][1] . $matches[1][1] . $matches[1][2] . $matches[1][2];
		}
		return $hex;
	}
	if ( 1 === preg_match( '/rgba?\(\s*([0-9]+)\s*,\s*([0-9]+)\s*,\s*([0-9]+)/i', $color, $m ) ) {
		$hex = '#';
		for ( $i = 1; $i <= 3; $i++ ) {
			$hex .= str_pad( dechex( max( 0, min( 255, (int) $m[ $i ] ) ) ), 2, '0', STR_PAD_LEFT );
		}
		return $hex;
	}
	return $color;
}
function storypress_blog_wrapper_classes() {
	$classes  = 'storypress-blog-layout-' . sanitize_html_class( storypress_get_option( 'blog_layout' ) ) . ' storypress-blog-width-' . sanitize_html_class( storypress_get_option( 'blog_width' ) );
	if ( ! storypress_get_option( 'archive_sidebar' ) || ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes .= ' storypress-blog-no-sidebar';
	}
	return $classes;
}
function storypress_menu_choices() { $choices = array( 0 => __( 'Use the registered theme location', 'storypress' ) ); foreach ( wp_get_nav_menus() as $menu ) { $choices[ $menu->term_id ] = $menu->name; } return $choices; }
function storypress_page_choices() { $choices = array( 0 => __( 'Latest posts (no dedicated page)', 'storypress' ) ); $pages = get_pages( array( 'sort_column' => 'post_title', 'sort_order' => 'ASC', 'number' => 200 ) ); foreach ( $pages as $page ) { $choices[ $page->ID ] = get_the_title( $page ); } return $choices; }
function storypress_social_networks() { return array( 'facebook'=>array('label'=>__('Facebook','storypress'),'placeholder'=>'https://facebook.com/yourpage','icon'=>'M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z'),'instagram'=>array('label'=>__('Instagram','storypress'),'placeholder'=>'https://instagram.com/yourhandle','icon'=>'M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z'),'twitter'=>array('label'=>__('X (Twitter)','storypress'),'placeholder'=>'https://x.com/yourhandle','icon'=>'M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932ZM17.61 20.644h2.039L6.486 3.24H4.298Z'),'youtube'=>array('label'=>__('YouTube','storypress'),'placeholder'=>'https://youtube.com/@yourchannel','icon'=>'M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z'),'pinterest'=>array('label'=>__('Pinterest','storypress'),'placeholder'=>'https://pinterest.com/yourhandle','icon'=>'M12 0C5.373 0 0 5.372 0 12c0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.401.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.354-.629-2.758-1.379l-.749 2.848c-.269 1.045-1.004 2.352-1.498 3.146 1.123.345 2.306.535 3.55.535 6.627 0 12-5.373 12-12 0-6.628-5.373-12-12-12z'),'linkedin'=>array('label'=>__('LinkedIn','storypress'),'placeholder'=>'https://linkedin.com/in/yourhandle','icon'=>'M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.225 0z'),'tiktok'=>array('label'=>__('TikTok','storypress'),'placeholder'=>'https://tiktok.com/@yourhandle','icon'=>'M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z') ); }
function storypress_option_checkbox($key,$label,$description){?>
<div class="storypress-control">
<div>
<label class="storypress-toggle">
<input type="checkbox" name="storypress_options[<?php echo esc_attr($key); ?>]" value="1" <?php checked(storypress_get_option($key)); ?>>
<span>
</span>
<strong>
<?php echo esc_html($label); ?>
</strong>
</label>
<p>
<?php echo esc_html($description); ?>
</p>
</div>
</div>
<?php }
function storypress_option_select($key,$label,$description,$choices,$options){?>
<div class="storypress-control">
<div>
<label for="storypress-<?php echo esc_attr($key); ?>">
<strong>
<?php echo esc_html($label); ?>
</strong>
</label>
<p>
<?php echo esc_html($description); ?>
</p>
</div>
<select id="storypress-<?php echo esc_attr($key); ?>" name="storypress_options[<?php echo esc_attr($key); ?>]">
<?php foreach($choices as $v=>$c): ?>
<option value="<?php echo esc_attr($v); ?>" <?php selected($options[$key],$v); ?>>
<?php echo esc_html($c); ?>
</option>
<?php endforeach; ?>
</select>
</div>
<?php }
function storypress_option_text($key,$label,$description,$options,$type='text',$placeholder=''){?>
<div class="storypress-control">
<div>
<label for="storypress-<?php echo esc_attr($key); ?>">
<strong>
<?php echo esc_html($label); ?>
</strong>
</label>
<p>
<?php echo esc_html($description); ?>
</p>
</div>
<input id="storypress-<?php echo esc_attr($key); ?>" type="<?php echo esc_attr($type); ?>" name="storypress_options[<?php echo esc_attr($key); ?>]" value="<?php echo esc_attr($options[$key]??''); ?>" <?php echo $placeholder?'placeholder="'.esc_attr($placeholder).'"':''; ?>>
</div>
<?php }
function storypress_option_number($key,$label,$description,$options,$min,$max,$step){?>
<div class="storypress-control">
<div>
<label for="storypress-<?php echo esc_attr($key); ?>">
<strong>
<?php echo esc_html($label); ?>
</strong>
</label>
<p>
<?php echo esc_html($description); ?>
</p>
</div>
<input id="storypress-<?php echo esc_attr($key); ?>" type="number" name="storypress_options[<?php echo esc_attr($key); ?>]" value="<?php echo esc_attr($options[$key]??''); ?>" min="<?php echo esc_attr($min); ?>" max="<?php echo esc_attr($max); ?>" step="<?php echo esc_attr($step); ?>">
</div>
<?php }
function storypress_option_textarea($key,$label,$description,$options,$rows=4){?>
<div class="storypress-control">
<div>
<label for="storypress-<?php echo esc_attr($key); ?>">
<strong>
<?php echo esc_html($label); ?>
</strong>
</label>
<p>
<?php echo esc_html($description); ?>
</p>
</div>
<textarea id="storypress-<?php echo esc_attr($key); ?>" rows="<?php echo esc_attr($rows); ?>" name="storypress_options[<?php echo esc_attr($key); ?>]"><?php echo esc_textarea($options[$key]??''); ?></textarea>
</div>
<?php }
function storypress_category_choices() { $choices=array(0=>__('All categories','storypress')); $terms=get_terms(array('taxonomy'=>'category','hide_empty'=>false,'number'=>300)); if(!is_wp_error($terms)){foreach($terms as $t){$choices[$t->term_id]=$t->name;}} return $choices; }
function storypress_tag_choices() { $choices=array(0=>__('All tags','storypress')); $terms=get_terms(array('taxonomy'=>'post_tag','hide_empty'=>false,'number'=>300)); if(!is_wp_error($terms)){foreach($terms as $t){$choices[$t->term_id]=$t->name;}} return $choices; }
function storypress_section_options($id,$label,$description,$options,$has_layout=false,$has_loadmore=false){$p='home_'.$id.'_';?>
<div class="storypress-card storypress-card--section">
<div class="storypress-card__head">
<strong>
<?php echo esc_html($label); ?>
</strong>
<p>
<?php echo esc_html($description); ?>
</p>
</div>
<?php storypress_option_checkbox($p.'enabled',__('Show this section','storypress'),__('Enable or hide this section on the home page.','storypress'));storypress_option_text($p.'title',__('Heading','storypress'),__('Leave blank to hide the section heading.','storypress'),$options);storypress_option_select($p.'cat',__('Category source','storypress'),__('Show posts only from this category. Leave on "All categories" to use the whole site.','storypress'),storypress_category_choices(),$options);storypress_option_select($p.'tag',__('Tag source','storypress'),__('Takes effect only when no category is chosen.','storypress'),storypress_tag_choices(),$options);if($has_layout){storypress_option_select($p.'layout',__('Layout','storypress'),__('List shows compact rows. Cards shows a grid of image cards.','storypress'),array('list'=>__('List','storypress'),'cards'=>__('Cards','storypress')),$options);}storypress_option_number($p.'count',__('Number of posts','storypress'),__('Between 1 and 12.','storypress'),$options,1,12,1);if($has_loadmore){storypress_option_checkbox($p.'loadmore',__('Show "Load more"','storypress'),__('Let readers continue through the older posts in this section.','storypress'));} ?>
</div>
<?php }
function storypress_option_logo($key,$label,$description,$current){?>
<div class="storypress-control">
<div>
<strong>
<?php echo esc_html($label); ?>
</strong>
<p>
<?php echo esc_html($description); ?>
</p>
</div>
<div class="storypress-logo-picker" data-logo-block>
<div class="storypress-logo-preview" data-logo-preview><?php if($current){echo wp_get_attachment_image($current,'thumbnail');} ?></div>
<input type="hidden" name="storypress_options[<?php echo esc_attr($key); ?>]" value="<?php echo esc_attr($current); ?>" data-logo-input>
<button type="button" class="button" data-select-logo><?php esc_html_e('Choose image','storypress'); ?></button>
<button type="button" class="button-link-delete" data-remove-logo <?php echo $current?'':'hidden'; ?>><?php esc_html_e('Remove','storypress'); ?></button>
</div>
</div>
<?php }
function storypress_option_visual($key,$label,$description,$choices,$current){?>
<div class="storypress-control storypress-control--visual">
<div>
<label id="storypress-label-<?php echo esc_attr($key); ?>">
<strong>
<?php echo esc_html($label); ?>
</strong>
</label>
<p>
<?php echo esc_html($description); ?>
</p>
</div>
<div class="storypress-visual-grid" role="radiogroup" aria-labelledby="storypress-label-<?php echo esc_attr($key); ?>">
<?php foreach($choices as $v=>$choice): ?>
<label class="storypress-visual-item">
<input type="radio" id="storypress-<?php echo esc_attr($key); ?>-<?php echo esc_attr($v); ?>" name="storypress_options[<?php echo esc_attr($key); ?>]" value="<?php echo esc_attr($v); ?>" <?php checked($current,$v); ?>>
<span class="storypress-visual-card" aria-hidden="true"><?php echo $choice['svg']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
<strong><?php echo esc_html($choice['title']); ?></strong>
<em><?php echo esc_html($choice['desc']); ?></em>
</label>
<?php endforeach; ?>
</div>
</div>
<?php }
function storypress_blog_layout_choices(){return array(
'list'=>array('title'=>__('Classic list','storypress'),'desc'=>__('Spacious rows with thumbnails — a comfortable daily reading rhythm.','storypress'),'svg'=>'<svg viewBox="0 0 120 84" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="2" y="3" width="34" height="24" rx="3" fill="#d8d3c9" /><rect x="42" y="5" width="74" height="6" rx="3" fill="#b3ac9f" /><rect x="42" y="16" width="56" height="6" rx="3" fill="#dcd8cf" /><rect x="2" y="32" width="34" height="24" rx="3" fill="#d8d3c9" /><rect x="42" y="34" width="74" height="6" rx="3" fill="#b3ac9f" /><rect x="42" y="45" width="56" height="6" rx="3" fill="#dcd8cf" /><rect x="2" y="61" width="34" height="24" rx="3" fill="#dcd8cf" /><rect x="42" y="63" width="74" height="6" rx="3" fill="#b3ac9f" /><rect x="42" y="74" width="56" height="6" rx="3" fill="#dcd8cf" /></svg>'),
'grid'=>array('title'=>__('Two-column grid','storypress'),'desc'=>__('Compact cards that let readers scan many stories at once.','storypress'),'svg'=>'<svg viewBox="0 0 120 84" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="2" y="3" width="56" height="38" rx="4" fill="#d8d3c9" /><rect x="62" y="3" width="56" height="38" rx="4" fill="#cfc8bd" /><rect x="2" y="46" width="56" height="38" rx="4" fill="#cfc8bd" /><rect x="62" y="46" width="56" height="38" rx="4" fill="#d8d3c9" /></svg>'),
'three-grid'=>array('title'=>__('Three-column grid','storypress'),'desc'=>__('A balanced three-column layout for scanning more stories at once.','storypress'),'svg'=>'<svg viewBox="0 0 120 84" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="2" y="3" width="36" height="36" rx="4" fill="#d8d3c9" /><rect x="42" y="3" width="36" height="36" rx="4" fill="#cfc8bd" /><rect x="82" y="3" width="36" height="36" rx="4" fill="#d8d3c9" /><rect x="2" y="45" width="36" height="36" rx="4" fill="#cfc8bd" /><rect x="42" y="45" width="36" height="36" rx="4" fill="#d8d3c9" /><rect x="82" y="45" width="36" height="36" rx="4" fill="#cfc8bd" /></svg>'),
);}
function storypress_blog_width_choices(){return array(
'compact'=>array('title'=>__('Compact','storypress'),'desc'=>__('A narrow reading column, focused like a book page.','storypress'),'svg'=>'<svg viewBox="0 0 120 84" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="42" y="6" width="36" height="9" rx="4.5" fill="#b3ac9f" /><rect x="42" y="21" width="36" height="6" rx="3" fill="#dcd8cf" /><rect x="42" y="32" width="36" height="6" rx="3" fill="#dcd8cf" /><rect x="42" y="43" width="36" height="6" rx="3" fill="#dcd8cf" /><rect x="42" y="54" width="36" height="6" rx="3" fill="#dcd8cf" /></svg>'),
'standard'=>array('title'=>__('Standard','storypress'),'desc'=>__('The balanced default width for most blogs.','storypress'),'svg'=>'<svg viewBox="0 0 120 84" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="30" y="6" width="60" height="9" rx="4.5" fill="#b3ac9f" /><rect x="30" y="21" width="60" height="6" rx="3" fill="#dcd8cf" /><rect x="30" y="32" width="60" height="6" rx="3" fill="#dcd8cf" /><rect x="30" y="43" width="60" height="6" rx="3" fill="#dcd8cf" /><rect x="30" y="54" width="60" height="6" rx="3" fill="#dcd8cf" /></svg>'),
'wide'=>array('title'=>__('Wide','storypress'),'desc'=>__('A spacious column that lets imagery breathe.','storypress'),'svg'=>'<svg viewBox="0 0 120 84" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="16" y="6" width="88" height="9" rx="4.5" fill="#b3ac9f" /><rect x="16" y="21" width="88" height="6" rx="3" fill="#dcd8cf" /><rect x="16" y="32" width="88" height="6" rx="3" fill="#dcd8cf" /><rect x="16" y="43" width="88" height="6" rx="3" fill="#dcd8cf" /><rect x="16" y="54" width="88" height="6" rx="3" fill="#dcd8cf" /></svg>'),
);}
function storypress_single_layout_choices(){return array(
'classic'=>array('title'=>__('Classic side TOC','storypress'),'desc'=>__('Article content with a sticky table of contents beside it.','storypress'),'svg'=>'<svg viewBox="0 0 120 84" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="4" y="7" width="22" height="5" rx="2.5" fill="#b3ac9f" /><rect x="4" y="17" width="45" height="4" rx="2" fill="#dcd8cf" /><rect x="4" y="30" width="70" height="38" rx="4" fill="#c7bfb2" /><rect x="82" y="30" width="32" height="5" rx="2.5" fill="#b3ac9f" /><rect x="82" y="40" width="25" height="4" rx="2" fill="#dcd8cf" /><rect x="82" y="49" width="28" height="4" rx="2" fill="#dcd8cf" /><rect x="82" y="58" width="20" height="4" rx="2" fill="#dcd8cf" /></svg>'),
'inline'=>array('title'=>__('Inline TOC','storypress'),'desc'=>__('Article content with the table of contents above the body.','storypress'),'svg'=>'<svg viewBox="0 0 120 84" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="26" y="6" width="68" height="6" rx="3" fill="#b3ac9f" /><rect x="26" y="17" width="48" height="4" rx="2" fill="#dcd8cf" /><rect x="18" y="29" width="84" height="13" rx="3" fill="#d8d3c9" /><rect x="18" y="48" width="84" height="31" rx="4" fill="#c7bfb2" /></svg>'),
);}
