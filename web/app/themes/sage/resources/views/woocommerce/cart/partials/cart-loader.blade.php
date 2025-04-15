<div x-show="loading" class="ht-container">
    <section class="">
        @php
            do_action( 'woocommerce_before_cart' );
        @endphp
    </section>
    <div class="grid grid-cols-12 gap-7">
        <section class="col-span-12 xl:col-span-9">
            <div class="sticky top-28">
                <div class="text-lg font-semibold border-b-[1.5px] border-primary pb-2">
                    <div class="flex items-center">
                    <div class="w-3/6"><div class="animate-pulse bg-slate-200 h-7 w-16"></div></div>
                    <div class="w-1/6 hidden md:block"><div class="animate-pulse bg-slate-200 h-7 w-20"></div></div>
                    <div class="w-1/6 hidden md:block"><div class="animate-pulse bg-slate-200 h-7 w-20"></div></div>
                    <div class="w-1/6 hidden md:block"><div class="animate-pulse bg-slate-200 h-7 w-20"></div></div>
                    </div>
                </div>
                <div class="flex flex-col">
                    {{-- product row --}}
                    @for ($i = 0; $i < 3; $i++)
                        <div class="w-full flex flex-col items-center py-5 border-b-[1.5px] border-primary">

                            <div class="w-full gap-3 flex">
                                <div class="bg-primary p-2.5 max-w-[100px] md:max-w-[60px] lg:hidden relative flex w-full aspect-square">
                                    <div class="animate-pulse bg-slate-200 h-full w-full"></div>
                                </div>
                                <div class="flex flex-col md:flex-row lg:w-full gap-0 pr-3 lg:pr-0 w-full">
                                    <div class="flex gap-3 md:w-3/6">
                                        <div class="bg-primary p-2.5 max-w-[60px] relative hidden lg:flex w-full aspect-square">
                                            <div class="animate-pulse bg-slate-200 h-full w-full"></div>
                                        </div>
                                        
                                        <div class=" h-full w-full flex flex-col">
                                            <div class=" h-full w-full flex flex-col lg:justify-start lg:w-full lg:max-w-[802px]">

                                                <div class="h-full w-full text-sm text-body max-w-xs font-semibold"><div class="animate-pulse bg-slate-200 h-full w-60"></div></div>
                
                                                <div class="h-full w-full text-xs text-body hidden lg:block">
                                                    <div class="animate-pulse bg-slate-200 h-full w-20"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-full flex flex-col sm:flex-row justify-between lg:justify-start md:w-3/6">
                                        <div class="h-full hidden sm:flex items-center text-lg font-bold text-body lg:w-1/3">
                                            <div class="animate-pulse bg-slate-200 h-full w-32"></div>
                                        </div>
                                        <div class="h-full flex flex-col-reverse sm:flex-row sm:items-center lg:w-2/3">
                                            <div class="h-full flex gap-4 lg:w-1/2 mr-2 ">
                                                <div class="animate-pulse bg-slate-200 h-full w-32"></div>
                                            </div>
                                            <div class="h-full w-full flex flex-col sm:flex-row sm:items-center text-lg font-bold text-body lg:justify-center mb-3 sm:mb-0">
                                                <div class="animate-pulse bg-slate-200 h-full w-32"></div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </section>

        {{-- totals --}}
        <div class="col-span-12 xl:col-span-3">
            
            <section class="">
                <div class="flex flex-col">
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-1 gap-4">

                        <div class="">
                        
                            <div class="animate-pulse bg-slate-200 w-16 h-7"></div>
                            <div class="w-full border-b-[1.5px] border-primary pb-2"></div>

                            <div class="flex justify-between items-center gap-4 mt-2">
                                <span class="animate-pulse bg-slate-200 w-16 h-4"></span>
                                <div class="animate-pulse bg-slate-200 w-16 h-4"></div>
                            </div>
                        </div>

                        <div>
                            {{-- Apply Coupon --}}
                            <div class="flex flex-col gap-5">
                                <p class="ht-custom form-row form-row-first mb-0">
                                    <label class="animate-pulse bg-slate-200 w-16 h-4"></label>
                                    <div class="animate-pulse bg-slate-200 w-full h-[42px]"></div>
                                </p>
                                <div 
                                    class="animate-pulse bg-slate-200 w-full h-[42px]">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-5">

                        <div class="pb-2 border-b-[1.5px] border-[#B6B6B6] flex items-center justify-between">
                            <div class="animate-pulse bg-slate-200 w-16 h-[42px]"></div>
                        </div>
                        
                        <div class="py-2 border-b-[1.5px] border-[#B6B6B6] flex items-center justify-between">
                            <div class="animate-pulse bg-slate-200 w-1/2 h-[42px]"></div>
                            <div class="animate-pulse bg-slate-200 w-1/2 h-[42px]"></div>
                        </div>

                        <div x-show="Number(shipping.shipping_total) > 0" class="py-2 border-b-[1.5px] border-[#B6B6B6] flex items-center justify-between">
                            <div class="animate-pulse bg-slate-200 w-1/2 h-[42px]"></div>
                            <div class="animate-pulse bg-slate-200 w-1/2 h-[42px]"></div>
                        </div>

                        <div class="py-2 flex items-center justify-between">
                            <div class="animate-pulse bg-slate-200 w-1/2 h-[42px]"></div>
                            <div class="animate-pulse bg-slate-200 w-1/2 h-[42px]"></div>
                        </div>

                    </div>
                </div>
            </section>

            {{-- buttons --}}
            <div class="flex flex-col gap-4 mt-5">
                <div class="animate-pulse bg-slate-200 h-[42px]">
                </div>
                <div class="animate-pulse bg-slate-200 h-[42px]"> 
                </div>
            </div>
            
        </div>
    </div>
    
</div>