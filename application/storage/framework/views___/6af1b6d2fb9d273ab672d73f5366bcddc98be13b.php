<div class="body" id="voucherFormVue" v-cloak>
	<form action="<?php echo e(route('check.ordervouchers', app()->getLocale())); ?>" method="POST" id="voucher_form" ref="orderForm">
		<?php echo e(csrf_field()); ?>


		<div class="row">
			<div class="col">
				<div class="form-group <?php echo e($errors->has('currency_code') ? ' has-error' : ''); ?>">
					<label for="currency_code"><?php echo e(__('Currency')); ?></label>
					<select id="currencies" name="currency_code" class="form-control"  @change="onChange($event)">
						<?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($currency['code']); ?>">
								<?php echo e($currency['code']); ?> 
							</option>	
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					 	
					</select>
				
					<?php if($errors->has('currency_code')): ?>
						<span class="help-block">
							<strong><?php echo e($errors->first('currency_code')); ?></strong>
						</span>
					<?php endif; ?>
				</div>
			</div>
			
		</div>
		<div class="row">
			<div class="col mt-4">
				<div class="form-group <?php echo e($errors->has('method') ? ' has-error' : ''); ?>">
					<label for="method mb-3"><?php echo e(__('Select Method')); ?></label>
					
					<input type="hidden" name="method" value="" ref="methodId">
					<?php if($errors->has('method')): ?>
						<span class="help-block">
							<strong><?php echo e($errors->first('method')); ?></strong>
						</span>
					<?php endif; ?>

					<div class="row">
						<button class="btn btn-primary btn-simple btn-round ml-3" v-bind:class = "(method.id === currentMethod.id) ? 'bg-green':'else_class'" v-for="method in methods" key="method.id"  @click.prevent="setMethod(method.id)">
							
									<strong  v-bind:class = "(method.id === currentMethod.id)?'true_class':'else_class'">{{method.name}}</strong>
								
							
						</button>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col">
				<div class="form-group <?php echo e($errors->has('balance_amount') ? ' has-error' : ''); ?>">
					<label for="balance_amount"><?php echo e(__('Amount')); ?></label>
					<input type="number" step="any"  name="amount"  id="balance_amount" class="form-control" v-model='amount'   required> 	
					<?php if($errors->has('amount')): ?>
						<span class="help-block ">
							<strong class="text-danger"><?php echo e($errors->first('amount')); ?></strong>
						</span>
					<?php endif; ?>
				</div>
			</div>
		</div>	
		
		<div class="row">
			<div class="col">
				<div class="float-right">
					
					<input type="submit" value="<?php echo e(__('Next')); ?>" @click.prevent="submitOrder" class="btn bg-blue btn-primary">
				</div>
			</div>
		</div>
	</form>       
</div>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/vue@next"></script>

<script>
	let app = Vue.createApp({
		data: function(){
			return {
				isLoading: false,
				currencies: <?php echo $json; ?>,
				currentCurrency:null,
				methods: null,
				currentMethod:null,
				amount:0
			}
		},
		methods:{
			setCurrency: function(val){

				try{
					var currency = this.currencies.find( obj =>{ 
						return obj.code === val;
					});
					this.methods = currency.methods;
					this.currentCurrency = currency;
				}catch(e){
					console.log(e);
				}
			},
			setMethod: function(val){
				try{
					var method = this.methods.find( obj =>{ 
							return obj.id === val;
						});
					this.currentMethod = method;
				}catch(e){
					console.log(e);
				}
				
			},
			onChange:function(evt){
				try{
					this.setCurrency(String(evt.target.value));
				}catch(e){
					console.log(e);
				}
			},
			submitOrder:function(evt){
				try{
					if(this.amount < this.currentMethod.min_deposit){
						this.amountError(0);
						return;
					}
					if( this.amount > this.currentMethod.max_deposit){
						this.amountError(1);
						return;
					}
					if(this.currentMethod !== null ){
						this.$refs.orderForm.submit();
					}
				}catch(e){
					console.log(e);
				}
				
			},
			amountError:function(direction){
				Swal.fire({
					title: 'Oops...',
					showConfirmButton: false,
					showDenyButton: true,
					text: `The Amount must be between ${ this.currentMethod.min_deposit }  and ${ this.currentMethod.max_deposit } for deposits via ${ this.currentMethod.name }`,
					denyButtonText: 'Fix',
					}).then((result) => {
						direction > 0 ? 
					   this.amount  = this.currentMethod.max_deposit : this.amount = this.currentMethod.min_deposit ;
					});
			}

		},
		watch: {
		    currentMethod: function (val) {
		      try{
					this.currentMethodId = val.id;
					this.$refs.methodId.value = val.id;

				}catch(e){
					console.log(e);
				}
		    },
		    amount: function(val){
		    	//console.log(val);
		    }
		},
		beforeMount: function(){
			try{
				this.methods = this.currencies[0].methods;
				this.currentMethod = this.methods[0];
				
			}catch(e){
				
			}
		},
		mounted: function(){
			try{
				
				this.$refs.methodId.value = this.currentMethod.id;

			}catch(e){
				console.log(e)
			}
		}
		


	});

	app.mount('#voucherFormVue')
</script>