<?php
/*
Plugin Name: Mortgage Loan Calculator
Version: 1.5
Description: Sidebar Widget that calculates your principal and interest mortgage loan payment after you enter the loan amount, interest rate and number of years of the loan. Validates that only numbers are entered.
Author: Lisa Plumb 
Author URI: http://wordpress.org/extend/plugins/profile/lisaplumb
Plugin URI: http://www.lisaplumb.com/widgets_gadgets/mortgage-loan-calculator-wordpress-widget/

 */
 
add_action('widgets_init', 'MortgageLoanCalculatorWidget');

function MortgageLoanCalculatorWidget() {
	register_widget('MortgageLoanCalculator');
}

class MortgageLoanCalculator extends WP_Widget {

	function MortgageLoanCalculator() {
		$widget_ops = array( 'classname' => 'mortgageloancalculator', 'description' => __('Mortgage Loan Calculator Sidebar Widget', 'mortgageloancalculator') );
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'mortgageloancalculator-widget' );
		$this->WP_Widget( 'mortgageloancalculator-widget', __('Mortgage Loan Calculator', 'mortgageloancalculator'), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
    	echo "<div id='mortgageloancalculator'>";
    	
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );

		echo $before_widget;
		if ( $title )
		echo $before_title . $title . $after_title;
		
    	echo "	<form name='mortgageloandata'>";
    	echo "	<br />";
    	echo "	<label>No commas or dollar signs please</label><br />";
    	echo "	<br />";   
    	echo "	<label><strong>Mortgage Loan Amount:</strong></label><br />";
    	echo "	$<input type='text' name='amount' size='12' id='mlc_amount' onchange='Calculate_mortgage();'>";
    	echo "	<br />";
    	echo "	<label><strong>Annual Interest Rate:</strong></label>";
    	echo "	<input type='text' name='interest' size='12' id='mlc_interest' onchange='Calculate_mortgage();'>%";
    	echo "	<br />";
		echo "	<label><strong>Loan Length:</strong></label><br />";	    
		echo "	<select name='years' id='mlc_years' onchange='Calculate_mortgage();'>";	
		echo "		<option value='10'>10 years</option>";
		echo "      <option value='15'>15 years</option>";
		echo "      <option value='20'>20 years</option>";
		echo "      <option value='30' selected='selected'>30 years</option>";
		echo "	</select>";	   	    	    
    	echo "	<br />";
    	echo "	<br />";
    	echo "	<input type='button' value='Calculate' id='mlc_button' onclick='Validate_mortgage();'><br />";
    	echo "	<br />";
    	echo "	<h3>Payment Information</h3>";
    	echo "    Your monthly principal and interest payment will be approximately:<br />";
		echo "    $<input type='text' name='monthlyPayment' size='12' id='mlc_monthlyPayment'><br />";
    	echo "	<br />";
   		echo "	<h3>Get your own <br /><a title='Get Your Own Mortgage Loan Calculator' href='http://www.lisaplumb.com/widgets_gadgets/'>Mortgage Loan Calculator</a></h3>";
	    echo "	</form>";
	    echo "	</div>";
	    
		//Start javascript
	    echo "<script type='text/javascript'>";
	    
		//Calculate function
	    echo "	function Calculate_mortgage() {";
	    echo "	loanAmount = document.getElementById('mlc_amount').value;";
	    echo "	loanInterest = document.getElementById('mlc_interest').value;";
	    echo "	loanLength = document.getElementById('mlc_years').value;";
		echo "	periodInterest = (loanInterest/100)/12;";
	    echo "	paymentsNumber = 12*loanLength;";
	    echo "	";
	    echo "	paymentValue = Math.floor((loanAmount*periodInterest)/(1-Math.pow((1+periodInterest),(-1*paymentsNumber)))*100)/100;";
	    echo "  if (!isNaN(paymentValue) && (paymentValue != Number.POSITIVE_INFINITY) && (paymentValue != Number.NEGATIVE_INFINITY)) {";
	    echo "  	document.getElementById('mlc_monthlyPayment').value=paymentValue;";
	    echo "  	} else {";
	    echo "  	document.getElementById('mlc_monthlyPayment')='';";
	    echo "  }";
	    echo "	}"; 
    
		//Validate function
		echo "	function Validate_mortgage(){";	    
		echo "	loanAmount = document.getElementById('mlc_amount').value;";
		echo "	loanInterest = document.getElementById('mlc_interest').value;";
 		echo "	if (isNaN(loanAmount)){";
 		echo "		alert('Mortgage loan amount must be a number (no dollar signs or commas).');";
 		echo "		}	else if (loanAmount== ''){";
 		echo "		alert('No mortgage loan amount was entered.');";
 		echo "		}	else {";
 		echo "		document.getElementById('mlc_amount').value=loanAmount;";
 		echo "	}";
 		echo "	if (isNaN(loanInterest)){";
 		echo "	 	alert('Interest rate must be a number (no percent signs or commas).');";
 		echo "	 	}	else if (loanInterest == ''){";
 		echo "		alert('No annual interest rate was entered.');";
 		echo "		}	else {";
 		echo "		document.getElementById('mlc_interest').value=loanInterest;";
 		echo "	}";
 		echo "	}";
 		
    	echo "</script>";
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		return $instance;
	}

	function form( $instance ) {
		$defaults = array( 'title' => __('Mortgage Loan Calculator', 'mortgageloancalculator'));
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'hybrid'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;">
		</p>

	<?php
	}
}
?>