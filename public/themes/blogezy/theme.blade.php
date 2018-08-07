<?php header('Content-type:text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="@yield('description', setting('description'))">
        <meta name="keywords" content="@yield('keywords', setting('keywords'))">

        <link rel="shortcut icon" href="/favicon.ico">

        <title>@yield('title') - {{ setting('title') }}</title>

        @yield('styles')
        @stack('styles')

        <!-- MENU CSS -->
        <link href="/themes/blogezy/assets/metismenu.min.css" rel="stylesheet" type="text/css">

        <!-- MATERIALDESIGN ICON -->
        <link rel="stylesheet" type="text/css" href="/themes/blogezy/assets/materialdesignicons.min.css">

        <!-- CUSTOM STYLESHEET -->
        <link href="/themes/blogezy/assets/style.css" rel="stylesheet" type="text/css">
        <link href="/themes/blogezy/assets/default.css" rel="stylesheet" type="text/css">
        <link rel="alternate" href="/news/rss" title="RSS News" type="application/rss+xml">
        <meta name="generator" content="RotorCMS {{ VERSION }}">
    </head>

    <body>
        <!-- Begin page -->
        <div id="wrapper">
            <!-- Top Bar Start -->
            <div class="topbar-mobile">
                <div class="logo">
                    <a href="/"><img src="/assets/img/images/logo.png" alt="{{ setting('title') }}"></a>

                    <button class="button-menu-mobile">
                        <i class="mdi mdi-menu"></i>
                    </button>
                </div>
            </div>
            <!-- Top Bar End -->
            <!-- ========== Left Sidebar Start ========== -->
            <div class="left side-menu">

                <div class="slimscroll-menu" id="remove-scroll">

                    <div class="logo">
                        <a href="/"><img src="/assets/img/images/logo.png" alt="{{ setting('title') }}"></a>
                        <p class="text-muted">Graduating from the halls of the University Of Western Sydney in late 2011. </p>
                    </div>

                    <!--- Sidemenu -->
                    <div id="sidebar-menu">
                        <!-- Left Menu Start -->
                        <ul class="metismenu" id="side-menu">

                            <li>
                                <a href="http://zoyothemes.com/blogezy/index.html"><span> Home</span></a>
                            </li>

                            <li>
                                <a href="javascript: void(0);"><span> Features </span></a>
                                <ul class="nav-second-level collapse" aria-expanded="false">
                                    <li><a href="http://zoyothemes.com/blogezy/standard-post.html">Standard Post</a></li>
                                    <li><a href="http://zoyothemes.com/blogezy/video-post.html">Video Post</a></li>
                                    <li><a href="http://zoyothemes.com/blogezy/audio-post.html">Audio Post</a></li>
                                    <li><a href="http://zoyothemes.com/blogezy/gallery-post.html">Gallery Post</a></li>
                                    <li><a href="http://zoyothemes.com/blogezy/quote-post.html">Quote Post</a></li>
                                    <li><a href="http://zoyothemes.com/blogezy/link-post.html">Link Post</a></li>
                                    <li><a href="http://zoyothemes.com/blogezy/404.html">Error 404</a></li>
                                </ul>
                            </li>

                            <li>
                                <a href="javascript: void(0);"><span> Lifestyle </span></a>
                            </li>

                            <li>
                                <a href="javascript: void(0);"><span> Travel </span></a>
                            </li>

                            <li>
                                <a href="javascript: void(0);"><span> Music </span></a>
                            </li>

                            <li>
                                <a href="http://zoyothemes.com/blogezy/about.html">About</a>
                            </li>

                            <li>
                                <a href="http://zoyothemes.com/blogezy/contact.html">Contact</a>
                            </li>

                        </ul>

                        <div class="copyright-box">
                            <p>&copy; 2005-{{ date('Y') }} {{ setting('title') }}</p>
                        </div>

                    </div>
                    <!-- Sidebar -->
                    <div class="clearfix"></div>

                </div>
                <!-- Sidebar -left -->

            </div>
            <!-- Left Sidebar End -->

            <div class="page-wrapper">
                {{--<section>
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="page-title">
                                    <div class="row">
                                        <div class="col-md-9 col-xs-12">
                                            <h2><span>News and Stories</span></h2>

                                            <p class="subtitle text-muted">Aenean sollicitudin, lorem quis bibendum auctor, nisi elit consequat ipsum, nec sagittis sem nibh id elit. Proin gravida nibh vel velit auctor Aenean sollicitudin, adipisicing elit sed lorem quis bibendum auctor.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>--}}

                <section class="mt-5 pb-5">
                    <div class="container">

                        <div class="row">
                            <!-- Content-->
                            <div class="col-xl-8">


                            @yield('advertTop')
                            @yield('advertUser')
                            @yield('note')
                            @yield('flash')
                            @yield('layout')
                            @yield('advertBottom')

                                <!-- Post-->
                                <article class="post">

                                    <div class="post-header">
                                        <h2 class="post-title"><a href="http://zoyothemes.com/blogezy/?#">Beautiful Day With Friends In Paris</a></h2>
                                        <ul class="post-meta">
                                            <li><i class="mdi mdi-calendar"></i> July 03, 2017</li>
                                            <li><i class="mdi mdi-tag-text-outline"></i> <a href="http://zoyothemes.com/blogezy/?#">Branding</a>, <a href="http://zoyothemes.com/blogezy/?#">Design</a></li>
                                            <li><i class="mdi mdi-comment-multiple-outline"></i> <a href="http://zoyothemes.com/blogezy/?#">3 Comments</a></li>
                                        </ul>
                                    </div>

                                    <div class="post-preview">
                                        <a href="http://zoyothemes.com/blogezy/?#"><img src="/themes/blogezy/assets/blog-1.jpg" alt="" class="img-fluid rounded"></a>
                                    </div>

                                    <div class="post-content">
                                        <p>Whether an identity or campaign, we make your brand visible, relevant and effective by placing the digital at the center of its ecosystem, without underestimating the power of traditional media. Whether an identity or campaign, we make your brand visible.</p>
                                    </div>

                                    <div><a href="http://zoyothemes.com/blogezy/?#" class="btn btn-outline-custom">Read More <i class="mdi mdi-arrow-right"></i></a></div>

                                </article>
                                <!-- Post end-->

                                <!-- Post-->
                                <article class="post">

                                    <div class="post-header">
                                        <h2 class="post-title"><a href="http://zoyothemes.com/blogezy/?#">Nature valley with cooling environment</a></h2>
                                        <ul class="post-meta">
                                            <li><i class="mdi mdi-calendar"></i> July 07, 2017</li>
                                            <li><i class="mdi mdi-tag-text-outline"></i> <a href="http://zoyothemes.com/blogezy/?#">Branding</a>, <a href="http://zoyothemes.com/blogezy/?#">Design</a></li>
                                            <li><i class="mdi mdi-comment-multiple-outline"></i> <a href="http://zoyothemes.com/blogezy/?#">3 Comments</a></li>
                                        </ul>
                                    </div>

                                    <div class="post-preview">
                                        <a href="http://zoyothemes.com/blogezy/?#"><img src="/themes/blogezy/assets/blog-2.jpg" alt="" class="img-fluid rounded"></a>
                                    </div>

                                    <div class="post-content">
                                        <p>Whether an identity or campaign, we make your brand visible, relevant and effective by placing the digital at the center of its ecosystem, without underestimating the power of traditional media. Whether an identity or campaign, we make your brand visible.</p>
                                    </div>

                                    <div><a href="http://zoyothemes.com/blogezy/?#" class="btn btn-outline-custom">Read More <i class="mdi mdi-arrow-right"></i></a></div>

                                </article>
                                <!-- Post end-->

                                <!-- Post-->
                                <article class="post">

                                    <div class="post-header">
                                        <h2 class="post-title"><a href="http://zoyothemes.com/blogezy/?#">Elegant, Simple &amp; Minimalist Blog Made With Love</a></h2>
                                        <ul class="post-meta">
                                            <li><i class="mdi mdi-calendar"></i> July 05, 2017</li>
                                            <li><i class="mdi mdi-tag-text-outline"></i> <a href="http://zoyothemes.com/blogezy/?#">Branding</a>, <a href="http://zoyothemes.com/blogezy/?#">Design</a></li>
                                            <li><i class="mdi mdi-comment-multiple-outline"></i> <a href="http://zoyothemes.com/blogezy/?#">3 Comments</a></li>
                                        </ul>
                                    </div>

                                    <div class="post-preview">
                                        <a href="http://zoyothemes.com/blogezy/?#"><img src="/themes/blogezy/assets/blog-3.jpg" alt="" class="img-fluid rounded"></a>
                                    </div>

                                    <div class="post-content">
                                        <p>Whether an identity or campaign, we make your brand visible, relevant and effective by placing the digital at the center of its ecosystem, without underestimating the power of traditional media. Whether an identity or campaign, we make your brand visible.</p>
                                    </div>

                                    <div><a href="http://zoyothemes.com/blogezy/?#" class="btn btn-outline-custom">Read More <i class="mdi mdi-arrow-right"></i></a></div>

                                </article>
                                <!-- Post end-->

                                <!-- Post-->
                                <article class="post">

                                    <div class="post-header">
                                        <h2 class="post-title"><a href="http://zoyothemes.com/blogezy/?#">15 Best Healthy and Easy Salad Recipes</a></h2>
                                        <ul class="post-meta">
                                            <li><i class="mdi mdi-calendar"></i> July 01, 2017</li>
                                            <li><i class="mdi mdi-tag-text-outline"></i> <a href="http://zoyothemes.com/blogezy/?#">Branding</a>, <a href="http://zoyothemes.com/blogezy/?#">Design</a></li>
                                            <li><i class="mdi mdi-comment-multiple-outline"></i> <a href="http://zoyothemes.com/blogezy/?#">3 Comments</a></li>
                                        </ul>
                                    </div>

                                    <div class="post-preview">
                                        <a href="http://zoyothemes.com/blogezy/?#"><img src="/themes/blogezy/assets/blog-4.jpg" alt="" class="img-fluid rounded"></a>
                                    </div>

                                    <div class="post-content">
                                        <p>Whether an identity or campaign, we make your brand visible, relevant and effective by placing the digital at the center of its ecosystem, without underestimating the power of traditional media. Whether an identity or campaign, we make your brand visible.</p>
                                    </div>

                                    <div><a href="http://zoyothemes.com/blogezy/?#" class="btn btn-outline-custom">Read More <i class="mdi mdi-arrow-right"></i></a></div>

                                </article>
                                <!-- Post end-->

                                <!-- Post-->
                                <article class="post">

                                    <div class="post-header">
                                        <h2 class="post-title"><a href="http://zoyothemes.com/blogezy/?#">Easy Homemade Candy Recipes and Ideas</a></h2>
                                        <ul class="post-meta">
                                            <li><i class="mdi mdi-calendar"></i> Jun 18, 2017</li>
                                            <li><i class="mdi mdi-tag-text-outline"></i> <a href="http://zoyothemes.com/blogezy/?#">Branding</a>, <a href="http://zoyothemes.com/blogezy/?#">Design</a></li>
                                            <li><i class="mdi mdi-comment-multiple-outline"></i> <a href="http://zoyothemes.com/blogezy/?#">3 Comments</a></li>
                                        </ul>
                                    </div>

                                    <div class="post-preview">
                                        <a href="http://zoyothemes.com/blogezy/?#"><img src="/themes/blogezy/assets/blog-5.jpg" alt="" class="img-fluid rounded"></a>
                                    </div>

                                    <div class="post-content">
                                        <p>Whether an identity or campaign, we make your brand visible, relevant and effective by placing the digital at the center of its ecosystem, without underestimating the power of traditional media. Whether an identity or campaign, we make your brand visible.</p>
                                    </div>

                                    <div><a href="http://zoyothemes.com/blogezy/?#" class="btn btn-outline-custom">Read More <i class="mdi mdi-arrow-right"></i></a></div>

                                </article>
                                <!-- Post end-->

                                <!-- Pagination-->
                                <div class="row">
                                    <div class="col-lg-12">
                                        <ul class="pagination">
                                            <li class="next"><a href="http://zoyothemes.com/blogezy/?#"><i class="fas fa-chevron-left"></i></a></li>
                                            <li class="active"><a href="http://zoyothemes.com/blogezy/?#">1</a></li>
                                            <li><a href="http://zoyothemes.com/blogezy/?#">2</a></li>
                                            <li><a href="http://zoyothemes.com/blogezy/?#">3</a></li>
                                            <li><a href="http://zoyothemes.com/blogezy/?#">4</a></li>
                                            <li><a href="http://zoyothemes.com/blogezy/?#">5</a></li>
                                            <li class="prev"><a href="http://zoyothemes.com/blogezy/?#"><i class="fas fa-chevron-right"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- Pagination end-->
                            </div>
                            <!-- Content end-->

                            <!-- Sidebar-->
                            <div class="col-xl-4">
                                <div class="sidebar">

                                    <!-- Search widget-->
                                    <aside class="widget widget_search">
                                        <form>
                                            <input class="form-control pr-5" type="search" placeholder="Search...">
                                            <button class="search-button" type="submit"><span class="mdi mdi-magnify"></span></button>
                                        </form>
                                    </aside>

                                    <aside class="widget about-widget">
                                        <div class="widget-title">About Me</div>

                                        <div class="text-center">
                                            <img src="/themes/blogezy/assets/photo.jpg" alt="About Me" class="rounded-circle">

                                            <p>Quis vero phasellus hac nullam, in quam vitae duis adipiscing mauris leo, laoreet eget at quis, ante vestibulum vivamus vel. Sapien lobortis, eget orci purus amet pede, consectetur neque risus.</p>
                                        </div>

                                    </aside>

                                    <aside class="widget about-widget">
                                        <div class="widget-title">Subscribe &amp; Follow</div>

                                        <ul class="socials">
                                            <li><a href="http://facebook.com/"><i class="mdi mdi-facebook"></i></a></li>
                                            <li><a href="http://twitter.com/"><i class="mdi mdi-twitter"></i></a></li>
                                            <li><a href="http://instagram.com/"><i class="mdi mdi-instagram"></i></a></li>
                                            <li><a href="http://pinterest.com/"><i class="mdi mdi-pinterest"></i></a></li>
                                        </ul>

                                    </aside>

                                    <!-- Categories widget-->
                                    <aside class="widget widget_categories">
                                        <div class="widget-title">Categories</div>
                                        <ul>
                                            <li><a href="http://zoyothemes.com/blogezy/?#">Journey</a> (40)</li>
                                            <li><a href="http://zoyothemes.com/blogezy/?#">Photography</a> (08)</li>
                                            <li><a href="http://zoyothemes.com/blogezy/?#">Lifestyle</a> (11)</li>
                                            <li><a href="http://zoyothemes.com/blogezy/?#">Food &amp; Drinks</a> (21)</li>
                                        </ul>
                                    </aside>

                                    <!-- Recent entries widget-->
                                    <aside class="widget widget_recent_entries_custom">
                                        <div class="widget-title">Popular Posts</div>
                                        <ul>
                                            <li class="clearfix">
                                                <div class="wi">
                                                    <a href="http://zoyothemes.com/blogezy/?#"><img src="/themes/blogezy/assets/img1.jpg" alt="" class="img-fluid"></a>
                                                </div>
                                                <div class="wb"><a href="http://zoyothemes.com/blogezy/?#">Beautiful Day With Friends..</a> <span class="post-date">Jun 15, 2017</span></div>
                                            </li>
                                            <li class="clearfix">
                                                <div class="wi">
                                                    <a href="http://zoyothemes.com/blogezy/?#"><img src="/themes/blogezy/assets/img2.jpg" alt="" class="img-fluid"></a>
                                                </div>
                                                <div class="wb"><a href="http://zoyothemes.com/blogezy/?#">Nature valley with cooling..</a> <span class="post-date">Jun 10, 2017</span></div>
                                            </li>
                                            <li class="clearfix">
                                                <div class="wi">
                                                    <a href="http://zoyothemes.com/blogezy/?#"><img src="/themes/blogezy/assets/img3.jpg" alt="" class="img-fluid"></a>
                                                </div>
                                                <div class="wb"><a href="http://zoyothemes.com/blogezy/?#">15 Best Healthy and Easy Salad..</a> <span class="post-date">Jun 8, 2017</span></div>
                                            </li>
                                        </ul>
                                    </aside>

                                    <!-- Text widget-->
                                    <aside class="widget">
                                        <div class="widget-title">Text Widget</div>

                                        <p class="text-muted text-widget-des">Exercitation photo booth stumptown tote bag Banksy, elit small batch freegan sed. Craft beer elit seitan exercitation, photo booth et 8-bit kale chips proident chillwave deep v laborum. Aliquip veniam delectus, Marfa eiusmod Pinterest in do umami readymade swag. </p>

                                    </aside>

                                    <!-- Archives widget-->
                                    <aside class="widget">
                                        <div class="widget-title">Archives</div>

                                        <ul>
                                            <li><a href="http://zoyothemes.com/blogezy/?#">March 2017</a> (40)</li>
                                            <li><a href="http://zoyothemes.com/blogezy/?#">April 2017</a> (08)</li>
                                            <li><a href="http://zoyothemes.com/blogezy/?#">May 2017</a> (11)</li>
                                            <li><a href="http://zoyothemes.com/blogezy/?#">Jun 2017</a> (21)</li>
                                        </ul>

                                    </aside>

                                    <!-- Tags widget-->
                                    <aside class="widget widget_tag_cloud">
                                        <div class="widget-title">Tags</div>
                                        <div class="tagcloud">
                                            <a href="http://zoyothemes.com/blogezy/?#">logo</a>
                                            <a href="http://zoyothemes.com/blogezy/?#">business</a>
                                            <a href="http://zoyothemes.com/blogezy/?#">corporate</a>
                                            <a href="http://zoyothemes.com/blogezy/?#">e-commerce</a>
                                            <a href="http://zoyothemes.com/blogezy/?#">agency</a>
                                            <a href="http://zoyothemes.com/blogezy/?#">responsive</a>
                                        </div>
                                    </aside>
                                </div>
                            </div>
                            <!-- Sidebar end-->
                        </div>

                    </div>
                    <!-- end container -->
                </section>
            </div>
        </div>

        @yield('scripts')
        @stack('scripts')

        <script src="/themes/blogezy/assets/jquery.easing.min.js"></script>
        <script src="/themes/blogezy/assets/metisMenu.min.js"></script>
        <!--common script for all pages-->
        <script src="/themes/blogezy/assets/jquery.app.js"></script>


</body></html>
