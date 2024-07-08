<!-- STATISTIC-1
   ============================================= -->
<div id="counter" class="py-100 statistic-section division">
    <div class="container">
        <!-- STATISTIC-1 WRAPPER -->
        <div class="statistic-1-wrapper">
            <div class="row justify-content-md-center row-cols-1 row-cols-md-3">
                <!-- STATISTIC BLOCK #1 -->
                <div class="col">
                    <div id="sb-1-1" class="wow fadeInUp">
                        <div class="statistic-block">
                            <!-- Digit -->
                            <div class="statistic-block-digit text-center">
                                <h2 class="s-46 statistic-number">
                                    <span class="count-element">{!! setting('counter1_value', app()->getLocale()) !!}
                                </span>{!! setting('counter1_unit', app()->getLocale()) !!}</h2>
                            </div>

                            <!-- Text -->
                            <div class="statistic-block-txt color--grey">
                                <p class="p-md">{!! setting('counter1_title', app()->getLocale()) !!}</p>
                            </div>
                        </div>
                    </div>
                </div> <!-- END STATISTIC BLOCK #1 -->
                <!-- STATISTIC BLOCK #2 -->
                <div class="col">
                    <div id="sb-1-2" class="wow fadeInUp">
                        <div class="statistic-block">
                            <!-- Digit -->
                            <div class="statistic-block-digit text-center">
                                <h2 class="s-46 statistic-number">
                                    <span class="count-element">
                                    {!! setting('counter2_value', app()->getLocale()) !!}
                                </span>
                                {!! setting('counter2_unit', app()->getLocale()) !!}
                            </h2>
                            </div>
                            <!-- Text -->
                            <div class="statistic-block-txt color--grey">
                                <p class="p-md">{!! setting('counter2_title', app()->getLocale()) !!}</p>
                            </div>
                        </div>
                    </div>
                </div> <!-- END STATISTIC BLOCK #2 -->
                <!-- STATISTIC BLOCK #3 -->
                <div class="col">
                    <div id="sb-1-3" class="wow fadeInUp">
                        <div class="statistic-block">
                            <!-- Digit -->
                            <div class="statistic-block-digit text-center">
                                <h2 class="s-46 statistic-number">
                                    <span class="count-element">{!! setting('counter3_value', app()->getLocale()) !!}</span>
                                    {!! setting('counter3_unit', app()->getLocale()) !!}
                                </h2>
                            </div>
                            <!-- Text -->
                            <div class="statistic-block-txt color--grey">
                                <p class="p-md">{!! setting('counter3_title', app()->getLocale()) !!}</p>
                            </div>
                        </div>
                    </div>
                </div> <!-- END STATISTIC BLOCK #3 -->
            </div> <!-- End row -->
        </div> <!-- END STATISTIC-1 WRAPPER -->
    </div> <!-- End container -->
</div> <!-- END STATISTIC-1 -->
