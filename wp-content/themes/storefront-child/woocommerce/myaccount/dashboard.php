<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

    $user = wp_get_current_user();
    $roles =  $user->roles;
    $user_id = $user->ID;
    if(in_array('partner',$roles)){
        $products_academy = get_partner_product_by_partner($user_id,'variable',3);
        $products_venue = get_partner_product_by_partner($user_id,'booking',3);
        $products_subscription= get_partner_product_by_partner($user_id,'variable-subscription',3);
        wc_get_template(
			'myaccount/partner-manager.php',
			array(
				'products_academy' => $products_academy,
				'products_venue'=>$products_venue,
				'products_subscription'=>$products_subscription
			)
		);
    }else{
$allowed_html = array(
	'a' => array(
		'href' => array(),
	),
);
?>

<p>
	<?php
	printf(
		/* translators: 1: user display name 2: logout url */
		wp_kses( __( 'Hello %1$s (not %1$s? <a href="%2$s">Log out</a>)', 'woocommerce' ), $allowed_html ),
		'<strong>' . esc_html( $current_user->display_name ) . '</strong>',
		esc_url( wc_logout_url() )
	);
	?>
</p>

<p>
	<?php
	/* translators: 1: Orders URL 2: Address URL 3: Account URL. */
	$dashboard_desc = __( 'From your account dashboard you can view your <a href="%1$s">recent orders</a>, manage your <a href="%2$s">billing address</a>, and <a href="%3$s">edit your password and account details</a>.', 'woocommerce' );
	if ( wc_shipping_enabled() ) {
		/* translators: 1: Orders URL 2: Addresses URL 3: Account URL. */
		$dashboard_desc = __( 'From your account dashboard you can view your <a href="%1$s">recent orders</a>, manage your <a href="%2$s">shipping and billing addresses</a>, and <a href="%3$s">edit your password and account details</a>.', 'woocommerce' );
	}
	printf(
		wp_kses( $dashboard_desc, $allowed_html ),
		esc_url( wc_get_endpoint_url( 'orders' ) ),
		esc_url( wc_get_endpoint_url( 'edit-address' ) ),
		esc_url( wc_get_endpoint_url( 'edit-account' ) )
	);
	?>
</p>
<?php 
    if(in_array('customer',$roles)){
		foreach($roles as $role){
			$member_role = $role;
		}
?>
<form method="post" action="/" name="delete_account_form">
<button class="toggle-user-status" data-user-id="<?php echo $user_id; ?>" data-user-role="<?php echo $member_role; ?>" data-nonce="<?php echo wp_create_nonce('toggle_customer_block_nonce'); ?>"><?php echo "Delete Your Account"; ?></button>
</form>
<?php } ?>
<?php
	/**
	 * My Account dashboard.
	 *
	 * @since 2.6.0
	 */
	do_action( 'woocommerce_account_dashboard' );

	/**
	 * Deprecated woocommerce_before_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_before_my_account' );

	/**
	 * Deprecated woocommerce_after_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_after_my_account' );
/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
}
if(isset($_POST['delete_account'])){	
	update_user_meta( $user_id, 'block_role', $roles );
	$user = new WP_User($user_id); 
	$user->set_role('');
}
?>
<script>
    let ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    document.addEventListener('DOMContentLoaded', function() {
        var toggleButtons = document.querySelectorAll('.toggle-user-status');
        toggleButtons.forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                var user_id = this.getAttribute('data-user-id');
                if (confirm('Are you sure you want to remove/block your account?')) {
                    var formData = new FormData();
                    formData.append('action', 'toggle_customer_block');
                    formData.append('user_id', user_id);
                    formData.append('nonce', this.getAttribute('data-nonce'));
                    this.innerText = "Blocking"
                    fetch(ajaxurl, {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
							this.innerText = "Blocked"
                            alert("Account Blocked");
                            window.location.href = '<?php echo site_url(); ?>';
                        } else {
							this.innerText = "Delete Your Account";
                            alert("Something went wrong");
                        }
                    })
                    .catch(error => {
                       // alert("Something went wrong");
                    });
                }
            });
        });
    });
</script>