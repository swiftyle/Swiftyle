<?php
    use Illuminate\Support\Facades\Session;
?>
<header class="main-nav">
    <div class="sidebar-user text-center">
        <a class="setting-primary" href="javascript:void(0)"><i data-feather="settings"></i></a><img class="img-90 rounded-circle" src="<?php echo e(session::get('avatar')); ?>" alt="" />
        <div class="badge-bottom"><span class="badge badge-primary">New</span></div>
        <a href="user-profile"> <h6 class="mt-3 f-14 f-w-600"><?php echo e(session::get('username')); ?></h6></a>
        <p class="mb-0 font-roboto"><?php echo e(session::get('role')); ?></p>
        <ul>
            <li>
                <span><span class="counter">19.8</span>k</span>
                <p>Follow</p>
            </li>
            <li>
                <span>2 year</span>
                <p>Experince</p>
            </li>
            <li>
                <span><span class="counter">95.2</span>k</span>
                <p>Follower</p>
            </li>
        </ul>
    </div>
    <nav>
        <div class="main-navbar">
            <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
            <div id="mainnav">
                <ul class="nav-menu custom-scrollbar">
                    <li class="back-btn">
                        <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
                    </li>
                    <li>
                        <a href="<?php echo e(route('index')); ?>" class="nav-link <?php echo e(prefixActive('/dashboard')); ?> <?php echo e(routeActive('index')); ?>" href="javascript:void(0)"><i data-feather="home"></i><span>Dashboard</span></a>                  
                        
                            
                            
                        
                    </li>
                    
                    
                    
                    
                    
                    <li class="dropdown">
                        <a class="nav-link menu-title <?php echo e(prefixActive('/icons')); ?>" href="javascript:void(0)"><i data-feather="command"></i><span>Icons</span></a>
                        <ul class="nav-submenu menu-content" style="display: <?php echo e(prefixBlock('/icons')); ?>;">
                            <li><a href="<?php echo e(route('flag-icon')); ?>" class="<?php echo e(routeActive('flag-icon')); ?>">Flag icon</a></li>
                            <li><a href="<?php echo e(route('font-awesome')); ?>" class="<?php echo e(routeActive('font-awesome')); ?>">Fontawesome Icon</a></li>
                            <li><a href="<?php echo e(route('ico-icon')); ?>" class="<?php echo e(routeActive('ico-icon')); ?>">Ico Icon</a></li>
                            <li><a href="<?php echo e(route('themify-icon')); ?>" class="<?php echo e(routeActive('themify-icon')); ?>">Thimify Icon</a></li>
                            <li><a href="<?php echo e(route('feather-icon')); ?>" class="<?php echo e(routeActive('feather-icon')); ?>">Feather icon</a></li>
                            <li><a href="<?php echo e(route('whether-icon')); ?>" class="<?php echo e(routeActive('whether-icon')); ?>">Whether Icon </a></li>
                        </ul>
                    </li>
                    
                    
                    
                    
                    
                    

                    <!-- Admin Panel -->
                    <li class="sidebar-main-title">
                        <div>
                            <h6>Database User</h6>
                        </div>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav <?php echo e(routeActive('data-user')); ?>" href="<?php echo e(route('data-user')); ?>"><i data-feather="database"></i><span>Data User</span></a>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav <?php echo e(routeActive('jsgrid-table')); ?>" href="<?php echo e(route('jsgrid-table')); ?>"><i data-feather="database"></i><span>Data Order</span></a>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav <?php echo e(routeActive('jsgrid-table')); ?>" href="<?php echo e(route('jsgrid-table')); ?>"><i data-feather="database"></i><span>Complain User</span></a>
                    </li>

                    <!-- Seller Panel -->
                    <li class="sidebar-main-title">
                        <div>
                            <h6>Database Seller</h6>
                        </div>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav <?php echo e(routeActive('jsgrid-table')); ?>" href="<?php echo e(route('jsgrid-table')); ?>"><i data-feather="database"></i><span>Add Shop</span></a>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav <?php echo e(routeActive('jsgrid-table')); ?>" href="<?php echo e(route('jsgrid-table')); ?>"><i data-feather="database"></i><span>Data Order</span></a>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav <?php echo e(routeActive('jsgrid-table')); ?>" href="<?php echo e(route('jsgrid-table')); ?>"><i data-feather="database"></i><span>Complain User</span></a>
                    </li>

                    <!-- Admin Panel -->
                    <li class="sidebar-main-title">
                        <div>
                            <h6>Speification Product</h6>
                        </div>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav <?php echo e(routeActive('jsgrid-table')); ?>" href="<?php echo e(route('jsgrid-table')); ?>"><i data-feather="grid"></i><span>Category</span></a>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav <?php echo e(routeActive('jsgrid-table')); ?>" href="<?php echo e(route('jsgrid-table')); ?>"><i data-feather="grid"></i><span>Product</span></a>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav <?php echo e(routeActive('jsgrid-table')); ?>" href="<?php echo e(route('jsgrid-table')); ?>"><i data-feather="grid"></i><span>Genre</span></a>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav <?php echo e(routeActive('jsgrid-table')); ?>" href="<?php echo e(route('jsgrid-table')); ?>"><i data-feather="grid"></i><span>Style</span></a>
                    </li>

                    <!-- Admin Panel -->
                    <li class="sidebar-main-title">
                        <div>
                            <h6>Database User</h6>
                        </div>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav <?php echo e(routeActive('jsgrid-table')); ?>" href="<?php echo e(route('jsgrid-table')); ?>"><i data-feather="database"></i><span>Data User</span></a>
                    </li>



                    <li class="dropdown">
                        <a class="nav-link menu-title <?php echo e(prefixActive('/data-tables')); ?>" href="javascript:void(0)"><i data-feather="database"></i><span>Manage Data User </span></a>
                        <ul class="nav-submenu menu-content" style="display: <?php echo e(prefixBlock('/data-tables')); ?>;">
                            <li><a href="<?php echo e(route('datatable-basic-init')); ?>" class="<?php echo e(routeActive('datatable-basic-init')); ?>">Basic Init</a></li>
                            <li><a href="<?php echo e(route('datatable-advance')); ?>" class="<?php echo e(routeActive('datatable-advance')); ?>">Advance Init</a></li>
                            <li><a href="<?php echo e(route('datatable-styling')); ?>" class="<?php echo e(routeActive('datatable-styling')); ?>">Styling</a></li>
                            <li><a href="<?php echo e(route('datatable-AJAX')); ?>" class="<?php echo e(routeActive('datatable-AJAX')); ?>">AJAX</a></li>
                            <li><a href="<?php echo e(route('datatable-server-side')); ?>" class="<?php echo e(routeActive('datatable-server-side')); ?>">Server Side</a></li>
                            <li><a href="<?php echo e(route('datatable-plugin')); ?>" class="<?php echo e(routeActive('datatable-plugin')); ?>">Plug-in</a></li>
                            <li><a href="<?php echo e(route('datatable-API')); ?>" class="<?php echo e(routeActive('datatable-API')); ?>">API</a></li>
                            <li><a href="<?php echo e(route('datatable-data-source')); ?>" class="<?php echo e(routeActive('datatable-data-source')); ?>">Data Sources</a></li>
                        </ul>
                    </li>

                    <li class="sidebar-main-title">
                        <div>
                            <h6>Table</h6>
                        </div>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link menu-title <?php echo e(prefixActive('/bootstrap-tables')); ?>" href="javascript:void(0)"><i data-feather="server"></i><span>Bootstrap Tables </span></a>
                        <ul class="nav-submenu menu-content" style="display: <?php echo e(prefixBlock('/bootstrap-tables')); ?>;">
                            <li><a href="<?php echo e(route('bootstrap-basic-table')); ?>" class="<?php echo e(routeActive('bootstrap-basic-table')); ?>">Basic Tables</a></li>
                            <li><a href="<?php echo e(route('bootstrap-sizing-table')); ?>" class="<?php echo e(routeActive('bootstrap-sizing-table')); ?>">Sizing Tables</a></li>
                            <li><a href="<?php echo e(route('bootstrap-border-table')); ?>" class="<?php echo e(routeActive('bootstrap-border-table')); ?>">Border Tables</a></li>
                            <li><a href="<?php echo e(route('bootstrap-styling-table')); ?>" class="<?php echo e(routeActive('bootstrap-styling-table')); ?>">Styling Tables</a></li>
                            <li><a href="<?php echo e(route('table-components')); ?>" class="<?php echo e(routeActive('table-components')); ?>">Table components</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link menu-title <?php echo e(prefixActive('/data-tables')); ?>" href="javascript:void(0)"><i data-feather="database"></i><span>Data Tables </span></a>
                        <ul class="nav-submenu menu-content" style="display: <?php echo e(prefixBlock('/data-tables')); ?>;">
                            <li><a href="<?php echo e(route('datatable-basic-init')); ?>" class="<?php echo e(routeActive('datatable-basic-init')); ?>">Basic Init</a></li>
                            <li><a href="<?php echo e(route('datatable-advance')); ?>" class="<?php echo e(routeActive('datatable-advance')); ?>">Advance Init</a></li>
                            <li><a href="<?php echo e(route('datatable-styling')); ?>" class="<?php echo e(routeActive('datatable-styling')); ?>">Styling</a></li>
                            <li><a href="<?php echo e(route('datatable-AJAX')); ?>" class="<?php echo e(routeActive('datatable-AJAX')); ?>">AJAX</a></li>
                            <li><a href="<?php echo e(route('datatable-server-side')); ?>" class="<?php echo e(routeActive('datatable-server-side')); ?>">Server Side</a></li>
                            <li><a href="<?php echo e(route('datatable-plugin')); ?>" class="<?php echo e(routeActive('datatable-plugin')); ?>">Plug-in</a></li>
                            <li><a href="<?php echo e(route('datatable-API')); ?>" class="<?php echo e(routeActive('datatable-API')); ?>">API</a></li>
                            <li><a href="<?php echo e(route('datatable-data-source')); ?>" class="<?php echo e(routeActive('datatable-data-source')); ?>">Data Sources</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link menu-title <?php echo e(prefixActive('/ex-data-tables')); ?>" href="javascript:void(0)"><i data-feather="hard-drive"></i><span>Ex. Data Tables </span></a>
                        <ul class="nav-submenu menu-content" style="display: <?php echo e(prefixBlock('/ex-data-tables')); ?>;">
                            <li><a href="<?php echo e(route('datatable-ext-autofill')); ?>" class="<?php echo e(routeActive('datatable-ext-autofill')); ?>">Auto Fill</a></li>
                            <li><a href="<?php echo e(route('datatable-ext-basic-button')); ?>" class="<?php echo e(routeActive('datatable-ext-basic-button')); ?>">Basic Button</a></li>
                            <li><a href="<?php echo e(route('datatable-ext-col-reorder')); ?>" class="<?php echo e(routeActive('datatable-ext-col-reorder')); ?>">Column Reorder</a></li>
                            <li><a href="<?php echo e(route('datatable-ext-fixed-header')); ?>" class="<?php echo e(routeActive('datatable-ext-fixed-header')); ?>">Fixed Header</a></li>
                            <li><a href="<?php echo e(route('datatable-ext-key-table')); ?>" class="<?php echo e(routeActive('datatable-ext-key-table')); ?>">Key Table</a></li>
                            <li><a href="<?php echo e(route('datatable-ext-responsive')); ?>" class="<?php echo e(routeActive('datatable-ext-responsive')); ?>">Responsive</a></li>
                            <li><a href="<?php echo e(route('datatable-ext-row-reorder')); ?>" class="<?php echo e(routeActive('datatable-ext-row-reorder')); ?>">Row Reorder</a></li>
                            <li><a href="<?php echo e(route('datatable-ext-scroller')); ?>" class="<?php echo e(routeActive('datatable-ext-scroller')); ?>">Scroller </a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link menu-title link-nav <?php echo e(routeActive('jsgrid-table')); ?>" href="<?php echo e(route('jsgrid-table')); ?>"><i data-feather="file-text"></i><span>Js Grid Table</span></a>
                    </li>
                    <li class="sidebar-main-title">
                        <div>
                            <h6>Applications</h6>
                        </div>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link menu-title <?php echo e(prefixActive('/project')); ?>" href="javascript:void(0)"><i data-feather="box"></i><span>Project </span></a>
                        <ul class="nav-submenu menu-content" style="display: <?php echo e(prefixBlock('/project')); ?>;">
                            <li><a href="<?php echo e(route('projects')); ?>" class="<?php echo e(routeActive('projects')); ?>">Project List</a></li>
                            <li><a href="<?php echo e(route('projectcreate')); ?>" class="<?php echo e(routeActive('projectcreate')); ?>">Create new </a></li>
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <a class="nav-link menu-title <?php echo e(prefixActive('/ecommerce')); ?>" href="javascript:void(0)"><i data-feather="shopping-bag"></i><span>Ecommerce</span></a>
                        <ul class="nav-submenu menu-content" style="display: <?php echo e(prefixBlock('/ecommerce')); ?>;">
                            <li><a href="<?php echo e(route('product')); ?>" class="<?php echo e(routeActive('product')); ?>">Product</a></li>
                            <li><a href="<?php echo e(route('product-page')); ?>" class="<?php echo e(routeActive('product-page')); ?>">Product page</a></li>
                            <li><a href="<?php echo e(route('list-products')); ?>" class="<?php echo e(routeActive('list-products')); ?>">Product list</a></li>
                            <li><a href="<?php echo e(route('payment-details')); ?>" class="<?php echo e(routeActive('payment-details')); ?>">Payment Details</a></li>
                            <li><a href="<?php echo e(route('order-history')); ?>" class="<?php echo e(routeActive('order-history')); ?>">Order History</a></li>
                            <li><a href="<?php echo e(route('invoice-template')); ?>" class="<?php echo e(routeActive('invoice-template')); ?>">Invoice</a></li>
                            <li><a href="<?php echo e(route('cart')); ?>" class="<?php echo e(routeActive('cart')); ?>">Cart</a></li>
                            <li><a href="<?php echo e(route('list-wish')); ?>" class="<?php echo e(routeActive('list-wish')); ?>">Wishlist</a></li>
                            <li><a href="<?php echo e(route('checkout')); ?>" class="<?php echo e(routeActive('checkout')); ?>">Checkout</a></li>
                            <li><a href="<?php echo e(route('pricing')); ?>" class="<?php echo e(routeActive('pricing')); ?>">Pricing</a></li>
                        </ul>
                    </li>
                    
                    <li class="dropdown">
                        <a class="nav-link menu-title <?php echo e(prefixActive('/chat')); ?>" href="javascript:void(0)"><i data-feather="message-circle"></i><span>Chat</span></a>
                        <ul class="nav-submenu menu-content" style="display: <?php echo e(prefixBlock('/chat')); ?>;">
                            <li><a href="<?php echo e(route('chat')); ?>" class="<?php echo e(routeActive('chat')); ?>">Chat App</a></li>
                            <li><a href="<?php echo e(route('chat-video')); ?>" class="<?php echo e(routeActive('chat-video')); ?>">Video chat</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link menu-title <?php echo e(prefixActive('/users')); ?>" href="javascript:void(0)"><i data-feather="users"></i><span>Users</span></a>
                        <ul class="nav-submenu menu-content" style="display: <?php echo e(prefixBlock('/users')); ?>;">
                            <li><a href="<?php echo e(route('user-profile')); ?>" class="<?php echo e(routeActive('user-profile')); ?>">Users Profile</a></li>
                            <li><a href="<?php echo e(route('edit-profile')); ?>" class="<?php echo e(routeActive('edit-profile')); ?>">Users Edit</a></li>
                            <li><a href="<?php echo e(route('user-cards')); ?>" class="<?php echo e(routeActive('user-cards')); ?>">Users Cards</a></li>
                        </ul>
                    </li>
                    
                    
                    
                    
                    <li>
                        <a class="nav-link menu-title link-nav <?php echo e(routeActive('sample-page')); ?>" href="<?php echo e(route('sample-page')); ?>"><i data-feather="file"></i><span>Sample page</span></a>
                    </li>
                    
                    <li class="mega-menu">
                        <a class="nav-link menu-title <?php echo e(prefixActive('/')); ?>" href="javascript:void(0)"><i data-feather="layers"></i><span>Others</span></a>
                        <div class="mega-menu-container menu-content" style="display: <?php echo e(prefixBlock('/')); ?>;">
                            <div class="container">
                                <div class="row">
                                    <div class="col mega-box">
                                        <div class="link-section">
                                            <div class="submenu-title">
                                                <h5>Error Page</h5>
                                            </div>
                                            <div class="submenu-content opensubmegamenu">
                                                <ul>
                                                    <li><a href="<?php echo e(route('error-page1')); ?>" class="<?php echo e(routeActive('error-page1')); ?>" target="_blank">Error page 1</a></li>
                                                    <li><a href="<?php echo e(route('error-page2')); ?>" class="<?php echo e(routeActive('error-page2')); ?>" target="_blank">Error page 2</a></li>
                                                    <li><a href="<?php echo e(route('error-page3')); ?>" class="<?php echo e(routeActive('error-page3')); ?>" target="_blank">Error page 3</a></li>
                                                    <li><a href="<?php echo e(route('error-page4')); ?>" class="<?php echo e(routeActive('error-page4')); ?>" target="_blank">Error page 4 </a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col mega-box">
                                        <div class="link-section">
                                            <div class="submenu-title">
                                                <h5>Authentication</h5>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="col mega-box">
                                        <div class="link-section">
                                            <div class="submenu-title">
                                                <h5>Coming Soon</h5>
                                            </div>
                                            <div class="submenu-content opensubmegamenu">
                                                <ul>
                                                    <li><a href="<?php echo e(route('comingsoon')); ?>" class="<?php echo e(routeActive('')); ?>">Coming Simple</a></li>
                                                    <li><a href="<?php echo e(route('comingsoon-bg-video')); ?>" class="<?php echo e(routeActive('')); ?>">Coming with Bg video</a></li>
                                                    <li><a href="<?php echo e(route('comingsoon-bg-img')); ?>" class="<?php echo e(routeActive('')); ?>">Coming with Bg Image</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col mega-box">
                                        <div class="link-section">
                                            <div class="submenu-title">
                                                <h5>Email templates</h5>
                                            </div>
                                            <div class="submenu-content opensubmegamenu">
                                                <ul>
                                                    <li><a href="<?php echo e(route('basic-template')); ?>" class="<?php echo e(routeActive('basic-template')); ?>">Basic Email</a></li>
                                                    <li><a href="<?php echo e(route('email-header')); ?>" class="<?php echo e(routeActive('email-header')); ?>">Basic With Header</a></li>
                                                    <li><a href="<?php echo e(route('template-email')); ?>" class="<?php echo e(routeActive('template-email')); ?>">Ecomerce Template</a></li>
                                                    <li><a href="<?php echo e(route('template-email-2')); ?>" class="<?php echo e(routeActive('template-email-2')); ?>">Email Template 2</a></li>
                                                    <li><a href="<?php echo e(route('ecommerce-templates')); ?>" class="<?php echo e(routeActive('ecommerce-templates')); ?>">Ecommerce Email</a></li>
                                                    <li><a href="<?php echo e(route('email-order-success')); ?>" class="<?php echo e(routeActive('email-order-success')); ?>">Order Success </a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="sidebar-main-title">
                        <div>
                            <h6>Miscellaneous</h6>
                        </div>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link menu-title <?php echo e(prefixActive('/gallery')); ?>" href="javascript:void(0)"><i data-feather="image"></i><span>Gallery</span></a>
                        <ul class="nav-submenu menu-content" style="display: <?php echo e(prefixBlock('/gallery')); ?>;">
                            <li><a href="<?php echo e(route('gallery')); ?>" class="<?php echo e(routeActive('gallery')); ?>">Gallery Grid</a></li>
                            <li><a href="<?php echo e(route('gallery-with-description')); ?>" class="<?php echo e(routeActive('gallery-with-description')); ?>">Gallery Grid Desc</a></li>
                            <li><a href="<?php echo e(route('gallery-masonry')); ?>" class="<?php echo e(routeActive('gallery-masonry')); ?>">Masonry Gallery</a></li>
                            <li><a href="<?php echo e(route('masonry-gallery-with-disc')); ?>" class="<?php echo e(routeActive('masonry-gallery-with-disc')); ?>">Masonry with Desc</a></li>
                            <li><a href="<?php echo e(route('gallery-hover')); ?>" class="<?php echo e(routeActive('gallery-hover')); ?>">Hover Effects</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link menu-title <?php echo e(prefixActive('/blog')); ?>" href="javascript:void(0)"><i data-feather="edit"></i><span>Blog</span></a>
                        <ul class="nav-submenu menu-content" style="display: <?php echo e(prefixBlock('/blog')); ?>;">
                            <li><a href="<?php echo e(route('blog')); ?>" class="<?php echo e(routeActive('blog')); ?>">Blog Details</a></li>
                            <li><a href="<?php echo e(route('blog-single')); ?>" class="<?php echo e(routeActive('blog-single')); ?>">Blog Single</a></li>
                            <li><a href="<?php echo e(route('add-post')); ?>" class="<?php echo e(routeActive('add-post')); ?>">Add Post</a></li>
                        </ul>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav <?php echo e(routeActive('faq')); ?>" href="<?php echo e(route('faq')); ?>"><i data-feather="help-circle"></i><span>FAQ</span></a>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link menu-title <?php echo e(prefixActive('/job-search')); ?>" href="javascript:void(0)"><i data-feather="user-check"></i><span>Job Search</span></a>
                        <ul class="nav-submenu menu-content" style="display: <?php echo e(prefixBlock('/job-search')); ?>;">
                            <li><a href="<?php echo e(route('job-cards-view')); ?>" class="<?php echo e(routeActive('job-cards-view')); ?>">Cards view</a></li>
                            <li><a href="<?php echo e(route('job-list-view')); ?>" class="<?php echo e(routeActive('job-list-view')); ?>">List View</a></li>
                            <li><a href="<?php echo e(route('job-details')); ?>" class="<?php echo e(routeActive('job-details')); ?>">Job Details</a></li>
                            <li><a href="<?php echo e(route('job-apply')); ?>" class="<?php echo e(routeActive('job-apply')); ?>">Apply</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link menu-title <?php echo e(prefixActive('/learning')); ?>" href="javascript:void(0)"><i data-feather="layers"></i><span>Learning</span></a>
                        <ul class="nav-submenu menu-content" style="display: <?php echo e(prefixBlock('/learning')); ?>;">
                            <li><a href="<?php echo e(route('learning-list-view')); ?>" class="<?php echo e(routeActive('learning-list-view')); ?>">Learning List</a></li>
                            <li><a href="<?php echo e(route('learning-detailed')); ?>" class="<?php echo e(routeActive('learning-detailed')); ?>">Detailed Course</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link menu-title <?php echo e(prefixActive('/maps')); ?>" href="javascript:void(0)"><i data-feather="map"></i><span>Maps</span></a>
                        <ul class="nav-submenu menu-content" style="display: <?php echo e(prefixBlock('/maps')); ?>;">
                            <li><a href="<?php echo e(route('map-js')); ?>" class="<?php echo e(routeActive('map-js')); ?>">Maps JS</a></li>
                            <li><a href="<?php echo e(route('vector-map')); ?>" class="<?php echo e(routeActive('vector-map')); ?>">Vector Maps</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a class="nav-link menu-title <?php echo e(prefixActive('/editors')); ?>" href="javascript:void(0)"><i data-feather="git-pull-request"></i><span>Editors</span></a>
                        <ul class="nav-submenu menu-content" style="display: <?php echo e(prefixBlock('/editors')); ?>;">
                            <li><a href="<?php echo e(route('summernote')); ?>" class="<?php echo e(routeActive('summernote')); ?>">Summer Note</a></li>
                            <li><a href="<?php echo e(route('ckeditor')); ?>" class="<?php echo e(routeActive('ckeditor')); ?>">CK editor</a></li>
                            <li><a href="<?php echo e(route('simple-MDE')); ?>" class="<?php echo e(routeActive('simple-MDE')); ?>">MDE editor</a></li>
                            <li><a href="<?php echo e(route('ace-code-editor')); ?>" class="<?php echo e(routeActive('ace-code-editor')); ?>">ACE code editor</a></li>
                        </ul>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav <?php echo e(routeActive('knowledgebase')); ?>" href="<?php echo e(route('knowledgebase')); ?>"><i data-feather="database"></i><span>Knowledgebase</span></a>
                    </li>
                </ul>
            </div>
            <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </div>
    </nav>
</header>
<?php /**PATH C:\xampp\htdocs\Swiftyle\resources\views/layouts/admin/partials/sidebar.blade.php ENDPATH**/ ?>