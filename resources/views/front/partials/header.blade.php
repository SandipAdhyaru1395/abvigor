<header class="main-header">
    <div class="header-upper header-constant  desktop-header ">
        <div class="auto-container">
            <div class="clearfix">

                <div class="pull-left logo-box">
                    <div class="logo"><a href="#"><img width="130px" height="34px" src="{{ asset('logo.png') }}"
                                alt="default-logo" title="default-logo"></a></div>
                </div>

                <div class="nav-outer clearfix">

                    <!-- Main Menu -->


                    <nav class="main-menu navbar-expand-md">

                        <div class="navbar-collapse collapse clearfix">
                            <ul class="navigation clearfix">

                                <li role="presentation" class="  ">
                                    <a href="{{ url('/') }}">Home</a>
                                </li>

                                <li role="presentation" class="dropdown   ">
                                    <a href="{{ url('/') }}/company">
                                        Company
                                    </a>
                                    <ul>

                                        <li role="presentation" class="  ">
                                            <a href="{{ url('/') }}/company/about-us">About us</a>
                                        </li>

                                        <li role="presentation" class="  ">
                                            <a href="{{ url('/') }}/ceos-message">CEO's Message</a>
                                        </li>

                                        <li role="presentation" class="  ">
                                            <a href="{{ url('/') }}/company/facilities">Facilities</a>
                                        </li>

                                        <li role="presentation" class="  ">
                                            <a href="{{ url('/') }}/company/vision-mission">Vision</a>
                                        </li>

                                        <li role="presentation" class="  ">
                                            <a href="{{ url('/') }}/company/career">Career</a>
                                        </li>


                                    </ul>
                                    <div class="dropdown-btn"><span class="fa fa-angle-down"></span></div>
                                </li>
                               <li role="presentation" class="dropdown   ">
                                    <a href="{{ url('/categories') }}">
                                        Products
                                    </a>
                                    <ul>
                                        @forelse($catalog_categories as $category)
                                            <li role="presentation" class=" @if($category->children->isNotEmpty()) dropdown @endif ">
                                                <a href="{{ '/product_category/' . $category->slug }}">
                                                    {{ $category->title }}
                                                </a>
                                                @if($category->children->isNotEmpty())
                                                    <div class="dropdown-btn"><span class="fa fa-angle-down"></span>
                                                    </div>
                                                
                                                    <ul>
                                                    @foreach($category->children as $child)
                                                        <li role="presentation" class="  ">
                                                            <a href="{{ '/product_category/' . $category->slug }}">{{ $child->title }}</a>
                                                        </li>
                                                    @endforeach
                                                    <!-- <li role="presentation" class="  ">
                                                        <a href="/products/diesel-engine-fuel-pipes">Diesel Engine Fuel
                                                            Pipes</a>
                                                    </li> -->

                                                    </ul>
                                                    <div class="dropdown-btn"><span class="fa fa-angle-down"></span></div>
                                                @endif
                                            </li>
                                        @empty
                                            <li role="presentation" class="">
                                                <a href="#">
                                                    No categories found
                                                </a>
                                            </li>
                                        @endforelse

                                    </ul>
                                </li>
                                <li role="presentation" class="  ">
                                    <a href="{{ url('/') }}/infrastructure">Infrastructure</a>
                                </li>

                                <li role="presentation" class="  ">
                                    <a href="{{ url('/') }}/quality/quality-policy">Quality</a>
                                </li>

                                <li role="presentation" class="dropdown   ">
                                    <a href="/">
                                        Media
                                    </a>
                                    <ul>

                                        <li role="presentation" class="  ">
                                            <a href="{{ url('/') }}/fitting-videos">Fitting Videos</a>
                                        </li>

                                        <li role="presentation" class="  ">
                                            <a href="{{ url('/') }}/brochure">Brochure</a>
                                        </li>


                                    </ul>
                                    <div class="dropdown-btn"><span class="fa fa-angle-down"></span></div>
                                </li>
                                <li role="presentation" class="  ">
                                    <a href="{{ url('/') }}/contact-us">Contact Us</a>
                                </li>


                            </ul>
                        </div>

                    </nav> <!--Button Box-->
                    <div class="button-box" id="order-button">
                        <!-- <a href="https://order.prestigeindia.co.in/order-form/" class="theme-btn btn-style-one">Order Form</a> -->
                        <a href="{{ route('order.add') }}" class="theme-btn btn-style-one">Order Form</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="sticky-header">
        <div class="auto-container">
            <div class="clearfix">
                <div class="pull-left logo-box">
                    <div class="logo"><a href="#"><img width="130px" height="34px" src="{{ asset('logo.png') }}"
                                alt="default-logo" title="default-logo"></a></div>
                </div>
                <div class="nav-outer clearfix">
                    <!-- Main Menu -->
                    <nav class="main-menu navbar-expand-md">
                        <div class="navbar-collapse collapse clearfix">
                            <ul class="navigation clearfix">

                                <li role="presentation" class="  ">
                                    <a href="{{ url('/') }}">Home</a>
                                </li>

                                <li role="presentation" class="dropdown   ">
                                    <a href="{{ url('/') }}/company">
                                        Company
                                    </a>
                                    <ul>

                                        <li role="presentation" class="  ">
                                            <a href="{{ url('/') }}/company/about-us">About us</a>
                                        </li>

                                        <li role="presentation" class="  ">
                                            <a href="{{ url('/') }}/ceos-message">CEO's Message</a>
                                        </li>

                                        <li role="presentation" class="  ">
                                            <a href="{{ url('/') }}/company/facilities">Facilities</a>
                                        </li>

                                        <li role="presentation" class="  ">
                                            <a href="{{ url('/') }}/company/vision-mission">Vision</a>
                                        </li>

                                        <li role="presentation" class="  ">
                                            <a href="{{ url('/') }}/company/career">Career</a>
                                        </li>


                                    </ul>
                                    <div class="dropdown-btn"><span class="fa fa-angle-down"></span></div>
                                </li>
                                <li role="presentation" class="dropdown   ">
                                    <a href="{{ url('/categories') }}">
                                        Products
                                    </a>
                                    <ul>
                                        @forelse($catalog_categories as $category)
                                            <li role="presentation" class=" @if($category->children->isNotEmpty()) dropdown @endif ">
                                                <a href="{{ '/product_category/' . $category->slug }}">
                                                    {{ $category->title }}
                                                </a>
                                                @if($category->children->isNotEmpty())
                                                    <div class="dropdown-btn"><span class="fa fa-angle-down"></span>
                                                    </div>
                                                
                                                    <ul>
                                                    @foreach($category->children as $child)
                                                        <li role="presentation" class="  ">
                                                            <a href="{{ '/product_category/' . $category->slug }}">{{ $child->title }}</a>
                                                        </li>
                                                    @endforeach
                                                    <!-- <li role="presentation" class="  ">
                                                        <a href="/products/diesel-engine-fuel-pipes">Diesel Engine Fuel
                                                            Pipes</a>
                                                    </li> -->

                                                    </ul>
                                                    <div class="dropdown-btn"><span class="fa fa-angle-down"></span></div>
                                                @endif
                                            </li>
                                        @empty
                                            <li role="presentation" class="">
                                                <a href="#">
                                                    No categories found
                                                </a>
                                            </li>
                                        @endforelse

                                    </ul>
                                </li>
                                <li role="presentation" class="  ">
                                    <a href="{{ url('/') }}/infrastructure">Infrastructure</a>
                                </li>

                                <li role="presentation" class="  ">
                                    <a href="{{ url('/') }}/quality/quality-policy">Quality</a>
                                </li>

                                <li role="presentation" class="dropdown   ">
                                    <a href="/">
                                        Media
                                    </a>
                                    <ul>

                                        <li role="presentation" class="  ">
                                            <a href="{{ url('/') }}/fitting-videos">Fitting Videos</a>
                                        </li>

                                        <li role="presentation" class="  ">
                                            <a href="{{ url('/') }}/brochure">Brochure</a>
                                        </li>


                                    </ul>
                                    <div class="dropdown-btn"><span class="fa fa-angle-down"></span></div>
                                </li>
                                <li role="presentation" class="  ">
                                    <a href="{{ url('/') }}/contact-us">Contact Us</a>
                                </li>


                                <!--<li class="button-box"><a href="https://order.prestigeindia.co.in/order-form/" class="theme-btn btn-style-five">Order Form</a></li>-->
                                <li class="button-box"><a href="{{ route('order.add') }}"
                                        class="theme-btn btn-style-five">Order
                                        Form</a></li>
                            </ul>
                        </div>

                    </nav> <!--Button Box-->
                </div>

            </div>
        </div>
    </div>
</header>