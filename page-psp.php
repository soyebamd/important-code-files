<?php


get_header();


// Set the start and end dates for the order query
$start_date = $_POST['start-date'];
$end_date = $_POST['end-date']; // Replace with your desired end date
$payment_method = $_POST['payment-method'];

$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );

?>

<div id="main-content">

<?php if ( ! $is_page_builder_used ) : ?>

	<div class="container">
		<div id="content-area" class="clearfix">
			<div id="left-area">

<?php endif; ?>

			<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<?php if ( ! $is_page_builder_used ) : ?>

					<h1 class="entry-title main_title"><?php the_title(); ?></h1>
				<?php
					$thumb = '';

					$width = (int) apply_filters( 'et_pb_index_blog_image_width', 1080 );

					$height = (int) apply_filters( 'et_pb_index_blog_image_height', 675 );
					$classtext = 'et_featured_image';
					$titletext = get_the_title();
					$alttext = get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true );
					$thumbnail = get_thumbnail( $width, $height, $classtext, $alttext, $titletext, false, 'Blogimage' );
					$thumb = $thumbnail["thumb"];

					if ( 'on' === et_get_option( 'divi_page_thumbnails', 'false' ) && '' !== $thumb )
						print_thumbnail( $thumb, $thumbnail["use_timthumb"], $alttext, $width, $height );
				?>

				<?php endif; ?>

					<div class="entry-content">
					<?php
						the_content();

						if ( ! $is_page_builder_used )
							wp_link_pages( array( 'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'Divi' ), 'after' => '</div>' ) );
					?>





<form action="" method="post">


<label for="date">Search- </label>

<input type="text" id="start-date" name="start-date" value="<?php if($_POST['start-date']) { echo $start_date;} ?>" placeholder="Start Date">

<input type="text" id="end-date" name="end-date" value="<?php if($_POST['end-date']) { echo $end_date;} ?>" placeholder="End Date">



<label for="date">Payment Method:</label>

<select name="payment-method" id="payment-method">
  <option value="stripe" <?php if($_POST['payment-method'] == 'stripe') { echo 'selected';} ?>>Stripe</option>
  <option value="ppcp-gateway" <?php if($_POST['payment-method'] == 'ppcp-gateway') { echo 'selected';} ?>>PayPal</option>
 <!-- <option value="cod" <?php if($_POST['payment-method'] == 'cod') { echo 'selected';} ?>>Cash on Delivery (COD)</option>-->
</select>



<input type="submit" name ="getResult" value="Get Result">



</form>


<?php

$orderTrue;
//strtotime
//$date = date('Y-m-d H:i:s', $timestamp);


if(!empty($start_date)  && !empty($end_date) && !empty($payment_method) ){

	
// Get completed orders
$completed_orders = wc_get_orders(array(
    'status' => 'completed',
    'date_completed' => strtotime($start_date) . '...' . strtotime($end_date),
	'payment_method' => $payment_method,
   
    'limit' => -1,
));

if($completed_orders){ $orderTrue = 1; }
else{ $orderTrue = 0;}
if($orderTrue == 0){
echo 'Not find any order in this date';	
}

// Loop through completed orders
foreach ($completed_orders as $order) {
	/*echo '<pre>';
	var_dump($order);
	echo '</pre>';
	*/
	

	$paymentText;

if($order->payment_method == 'ppcp-gateway'){

	$paymentText = 'PayPal';

}

else if($order->payment_method == 'stripe'){

	$paymentText = 'Stripe';

}

else { 	$paymentText = $order->payment_method; }


    // Output order details
    echo 'Ticket ID: ' . $order->get_id() . '<br>';
    echo 'Ticket Total: ' . $order->get_formatted_order_total() . '<br>';
    echo 'Payment Status: ' . $order->get_status() . '<br>';

	echo '<b style="color:black">Payment Method: ' . $paymentText . '<br></b>';

	echo 'Ticket Order Completion Date: ' . $order->get_date_completed()->format('Y-m-d H:i:s') . '<br>';


    // Output billing details
    $billing_address = $order->get_address('billing');
    echo 'Billing First Name: ' . $billing_address['first_name'] . '<br>';
    echo 'Billing Last Name: ' . $billing_address['last_name'] . '<br>';
    echo 'Billing Email: ' . $billing_address['email'] . '<br>';

    // Output shipping details
    $shipping_address = $order->get_address('shipping');
    echo 'Shipping First Name: ' . $shipping_address['first_name'] . '<br>';
    echo 'Shipping Last Name: ' . $shipping_address['last_name'] . '<br>';
    echo 'Shipping Address: ' . $shipping_address['address_1'] . ', ' . $shipping_address['city'] . ', ' . $shipping_address['state'] . ', ' . $shipping_address['postcode'] . '<br>';

    // Output ordered products
    foreach ($order->get_items() as $item_id => $item) {
        echo 'Ticket Name: ' . $item->get_name() . '<br>';
        echo 'Ticket Quantity: ' . $item->get_quantity() . '<br>';
        echo 'Ticket Price: ' . wc_price($item->get_total()) . '<br>';
    }
	

	

	

    echo '<hr>'; // Add a horizontal line between orders
}

}



else { echo "Select correct range of date please <br>"; }


?>


					</div>

				<?php
					if ( ! $is_page_builder_used && comments_open() && 'on' === et_get_option( 'divi_show_pagescomments', 'false' ) ) comments_template( '', true );
				?>

				</article>

			<?php endwhile; ?>

<?php if ( ! $is_page_builder_used ) : ?>

			</div>

			<?php get_sidebar(); ?>
		</div>
	</div>

<?php endif; ?>


</div>


<?php

get_footer();

?>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script>

jQuery(function($){ 


	 
    $( "#start-date" ).datepicker({

		//  maxDate: "+1m +1w"
		 maxDate: "+0d"		

	});


	 $( "#end-date" ).datepicker({

		 maxDate: "+1d"
		
	});
 



})



</script>