<?php

class PFModifications {
	public static function init() {
		static $instance;

		if ( ! is_a( $instance, 'PFModifications' ) ) {
			$instance = new self();
		}

		return $instance;
	}


	/**
	 * Construct function.
	 *
	 * See http://php.net/manual/en/language.oop5.decon.php to get a better understanding of what's going on here.
	 *
	 * @since 1.7
	 *
	 */
	private function __construct() {
		$this->fire_hooks();
	}

	public function fire_hooks(){
		add_action( 'transition_pf_post_meta', array($this, 'process_zenodo_meta') );
		add_action( 'establish_pf_metas', array($this, 'establish_zenodo_meta') );
		add_action( 'nominate_this_sidebar_top', array($this, 'zenodo_required_meta_fields') );

		add_filter( 'pf_author_nominate_this_prompt', function(){ return 'Enter author names, semicolon seperated, in the following format: [last-name], [first-name]; ...'; } );
	}

	public function process_zenodo_meta( $old_id, $new_id ){
		$this->zenodo_meta_maker($new_id);
	}

	public function establish_zenodo_meta( $id, $args ){
		$this->zenodo_meta_maker($id);
	}

	public function zenodo_meta_maker( $id ){

	}

	public function zenodo_required_meta_fields(){
		?>
			<div id="zenododiv" class="postbox">
				<div class="handlediv" title="<?php esc_attr_e( 'Click to toggle' ); ?>"><br /></div>
				<h3 class="hndle"><?php _e('Bibliographic Metadata') ?></h3>
				<div class="inside">
					<label for="pf_affiliations"><input type="text" id="pf_affiliations" name="pf_affiliations" value=""/><br />&nbsp;<?php _e('Enter Author Affiliations, semicolon seperated, in same order as authors.', 'pf'); ?></label><hr />
					<?php
						$title = isset( $_GET['t'] ) ? trim( strip_tags( html_entity_decode( stripslashes( $_GET['t'] ) , ENT_QUOTES) ) ) : '';
						$tite = esc_html( $title );
					?>
					<label for="pf-source-title"><textarea type="text" id="pf-source-title" rows="5" cols="20" name="pf-source-title"><?php echo $title; ?></textarea><br />&nbsp;<?php _e('Override source title.', 'pf'); ?></label><hr />
					<label for="pf-keywords"><input type="text" id="pf-keywords" name="pf-keywords" value=""/><br />&nbsp;<?php _e('Enter Keywords, semicolon seperated.', 'pf'); ?></label><hr />
					<label for="pf-references"><input type="text" id="pf-references" name="pf-references" value=""/><br />&nbsp;<?php _e('Enter Referance Identifiers, semicolon seperated.', 'pf'); ?></label><hr />
					<label for="pf-item-date"><input type="text" id="pf-item-date" name="pf-item-date" value=""/><br />&nbsp;<?php _e('Enter published date in the format YYYY-MM-DD, if that date is not today.', 'pf'); ?></label><hr />
					<label for="pf-abstract"><textarea id="pf-abstract" name="pf-abstract" value="" rows="10" cols="20"></textarea><br />&nbsp;<?php _e('Enter an abstract, if applicable.', 'pf'); ?></label><hr />
					<label for="pf-licence">
						<select type="text" id="pf-license" name="pf-license">
							<option value="cc-zero">CC-0</option>
							<option value="cc-by">CC-BY</option>
						</select><br />
						CC0  :: “I don’t care about attribution”
						CC-By :: “I want to receive attribution”
					</label><hr />
				</div>

			</div>
		<?php
	}


}

function pf_modifications() {
	return PFModifications::init();
}

// Start me up!
pf_modifications();