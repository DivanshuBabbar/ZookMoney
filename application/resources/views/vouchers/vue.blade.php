<script >
var withdrawal_form = new Vue({
	el: '#voucher_form',
	data:{
		total: 0,
		amount:0
	},
	methods: {
		totalize : function(evt){
			this.total =  (evt.target.value - ( (({{general_setting('mt_percentage_fee')}}/100) * evt.target.value) + {{general_setting('mt_fixed_fee')}} )).toFixed(2); 
		},
		voucher_balance : function (evt) {
			var numer  = evt.target.value ;
			var value =  ( (({{general_setting('mt_percentage_fee')}}/100) *  numer ) + {{general_setting('mt_fixed_fee')}} ) ;
			this.amount = number( (value + numer).toFixed(2) ) ; 

			console.log(this.amount);
			console.log(value);
			console.log(numer);
		},
	}
});
</script>