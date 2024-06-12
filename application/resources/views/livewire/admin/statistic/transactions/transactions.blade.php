<div class="w-full xl:w-8/12 mb-12 xl:mb-0 px-4">
	<div class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded bg-blueGray-700">
		<div class="rounded-t mb-0 px-4 py-3 bg-transparent">
			<div class="flex flex-wrap items-center">
				<div class="relative w-full max-w-full flex-grow flex-1">
					<h6 class="uppercase text-blueGray-100 mb-1 text-xs font-semibold">Transactions Overview</h6>
					<h2 class="text-white text-xl font-semibold">Volume per Month</h2></div>
			</div>
		</div>
		<div class="p-4 flex-auto">
			<div class="relative h-350-px">
				<div class="chartjs-size-monitor">
					<div class="chartjs-size-monitor-expand">
						<div></div>
					</div>
					<div class="chartjs-size-monitor-shrink">
						<div></div>
					</div>
				</div>
				<canvas id="line-chart" style="display:block;width:767px;height:350px" width="767" height="350" class="chartjs-render-monitor"></canvas>
			</div>
		</div>
	</div>
</div>