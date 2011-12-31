            <div class="row">

                <div class="span4 profile-stats">

                    <?php echo Mini::avatar($user->id); ?>

					<?php if ($user->id != Sentry::user()->get('id')): ?>
					<ul>
						<li><?php echo Mini::follow_link($user->username, $user->id, ($user->follow_id ? 'unfollow' : 'follow')); ?></li>
					</ul>
					<?php endif; ?>
					
                    <ul>
                        <li><span>Posts</span>0</li>
                        <li><span>Comments</span>0</li>
                        <li><span>Likes</span>0</li>
                        <li><span>Awards</span>0</li>
                    </ul>
                    <ul>
                        <li><span>Joined</span><?php echo Date::time_ago($user->created_at); ?></li>
                        <li><span>Gender</span><?php echo $user->gender; ?></li>
                        <li><span>Brithday</span><?php echo Date::forge($user->birthdate)->format("%m/%d/%Y"); ?></li>
                        <li><span>Location</span><?php echo $user->location; ?></li>
                    </ul>
                    <ul>
                        <li><span>Last IP</span>120.0.0.1</li>
                        <li><span>Registered IP</span>120.0.0.1</li>
                    </ul>
                </div>
                
                <div class="span12">
                    
                    <?php if ( ! empty($user->about)): ?>
                    <p><?php echo $user->about; ?></p><br />
                    <?php endif; ?>
                    
                    <ul data-tabs="tabs" class="tabs">
                        <li class="active"><a href="#wall">Wall</a></li>
                        <li class=""><a href="#recent">Recent Activity</a></li>
                        <li class=""><a href="#posts">Posts</a></li>
                        <li><a href="#likes">Likes</a></li>
                        <li><a href="#comments">Comments</a></li>
                    </ul>
                    <div class="tab-content" id="my-tab-content">
                        <div id="wall" class="tab-pane active">
                            <p>Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terry richardson ex squid. Aliquip placeat salvia cillum iphone. Seitan aliquip quis cardigan american apparel, butcher voluptate nisi qui.</p>
                        </div>
                        <div id="recent" class="tab-pane">
                            <p>Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee. Qui photo booth letterpress, commodo enim craft beer mlkshk aliquip jean shorts ullamco ad vinyl cillum PBR. Homo nostrud organic, assumenda labore aesthetic magna delectus mollit. Keytar helvetica VHS salvia yr, vero magna velit sapiente labore stumptown. Vegan fanny pack odio cillum wes anderson 8-bit, sustainable jean shorts beard ut DIY ethical culpa terry richardson biodiesel. Art party scenester stumptown, tumblr butcher vero sint qui sapiente accusamus tattooed echo park.</p>
                        </div>
                        
                        <div id="posts" class="tab-pane">
                            <ol class="row-border">
                                <li>
                                    <div class="pull-left" style="width:50px;">
                                        <a data-avatarhtml="true" class="avatar Av5783s" href="users/dregond-rahl-1/"><img width="48" height="48" alt="DregondRahl" src="http://www.gravatar.com/avatar/95621959b202ebab13159aba096aca64.jpg?s=48&amp;d=http%3A%2F%2Fxenforo.com%2Fcommunity%2Fstyles%2Fdefault%2Fxenforo%2Favatars%2Favatar_male_s.png"></a>
                                    </div>

                                    <div class="row-right">
                                        <div class="titleText">
                                            <h3 class="title"><a href="#">Lots of Sub-Boards</a></h3>
                                        </div>

                                        <blockquote class="snippet">
                                            <a href="#">The problem is Xenforo runs that query to load the mainpage. Which is all the forums and nodes...</a>
                                        </blockquote>

                                        <div class="meta">
                                            Post by: <a class="username" href="users/dregond-rahl-1">DregondRahl</a>,
                                            <span title="Nov 5, 2011 at 5:17 AM" class="DateTime">Nov 5, 2011</span>
                                            in forum: <a href="#">General</a>
                                        </div>
                                    </div>
                                </li>
                                
                                <li>
                                    <div class="pull-left" style="width:50px;">
                                        <a data-avatarhtml="true" class="avatar Av5783s" href="users/dregond-rahl-1/"><img width="48" height="48" alt="DregondRahl" src="http://www.gravatar.com/avatar/95621959b202ebab13159aba096aca64.jpg?s=48&amp;d=http%3A%2F%2Fxenforo.com%2Fcommunity%2Fstyles%2Fdefault%2Fxenforo%2Favatars%2Favatar_male_s.png"></a>
                                    </div>

                                    <div class="row-right">
                                        <div class="titleText">
                                            <h3 class="title"><a href="#">Lots of Sub-Boards</a></h3>
                                        </div>

                                        <blockquote class="snippet">
                                            <a href="#">The problem is Xenforo runs that query to load the mainpage. Which is all the forums and nodes...</a>
                                        </blockquote>

                                        <div class="meta">
                                            Post by: <a class="username" href="users/dregond-rahl-1">DregondRahl</a>,
                                            <span title="Nov 5, 2011 at 5:17 AM" class="DateTime">Nov 5, 2011</span>
                                            in forum: <a href="#">General</a>
                                        </div>
                                    </div>
                                </li>
                                
                                <li>
                                    <div class="pull-left" style="width:50px;">
                                        <a data-avatarhtml="true" class="avatar Av5783s" href="users/dregond-rahl-1/"><img width="48" height="48" alt="DregondRahl" src="http://www.gravatar.com/avatar/95621959b202ebab13159aba096aca64.jpg?s=48&amp;d=http%3A%2F%2Fxenforo.com%2Fcommunity%2Fstyles%2Fdefault%2Fxenforo%2Favatars%2Favatar_male_s.png"></a>
                                    </div>

                                    <div class="row-right">
                                        <div class="titleText">
                                            <h3 class="title"><a href="#">Lots of Sub-Boards</a></h3>
                                        </div>

                                        <blockquote class="snippet">
                                            <a href="#">The problem is Xenforo runs that query to load the mainpage. Which is all the forums and nodes...</a>
                                        </blockquote>

                                        <div class="meta">
                                            Post by: <a class="username" href="users/dregond-rahl-1">DregondRahl</a>,
                                            <span title="Nov 5, 2011 at 5:17 AM" class="DateTime">Nov 5, 2011</span>
                                            in forum: <a href="#">General</a>
                                        </div>
                                    </div>
                                </li>
                                
                                <li>
                                    <div class="pull-left" style="width:50px;">
                                        <a data-avatarhtml="true" class="avatar Av5783s" href="users/dregond-rahl-1/"><img width="48" height="48" alt="DregondRahl" src="http://www.gravatar.com/avatar/95621959b202ebab13159aba096aca64.jpg?s=48&amp;d=http%3A%2F%2Fxenforo.com%2Fcommunity%2Fstyles%2Fdefault%2Fxenforo%2Favatars%2Favatar_male_s.png"></a>
                                    </div>

                                    <div class="row-right">
                                        <div class="titleText">
                                            <h3 class="title"><a href="#">Lots of Sub-Boards</a></h3>
                                        </div>

                                        <blockquote class="snippet">
                                            <a href="#">The problem is Xenforo runs that query to load the mainpage. Which is all the forums and nodes...</a>
                                        </blockquote>

                                        <div class="meta">
                                            Post by: <a class="username" href="users/dregond-rahl-1">DregondRahl</a>,
                                            <span title="Nov 5, 2011 at 5:17 AM" class="DateTime">Nov 5, 2011</span>
                                            in forum: <a href="#">General</a>
                                        </div>
                                    </div>
                                </li>
                                
                                <li>
                                    <div class="pull-left" style="width:50px;">
                                        <a data-avatarhtml="true" class="avatar Av5783s" href="users/dregond-rahl-1/"><img width="48" height="48" alt="DregondRahl" src="http://www.gravatar.com/avatar/95621959b202ebab13159aba096aca64.jpg?s=48&amp;d=http%3A%2F%2Fxenforo.com%2Fcommunity%2Fstyles%2Fdefault%2Fxenforo%2Favatars%2Favatar_male_s.png"></a>
                                    </div>

                                    <div class="row-right">
                                        <div class="titleText">
                                            <h3 class="title"><a href="#">Lots of Sub-Boards</a></h3>
                                        </div>

                                        <blockquote class="snippet">
                                            <a href="#">The problem is Xenforo runs that query to load the mainpage. Which is all the forums and nodes...</a>
                                        </blockquote>

                                        <div class="meta">
                                            Post by: <a class="username" href="users/dregond-rahl-1">DregondRahl</a>,
                                            <span title="Nov 5, 2011 at 5:17 AM" class="DateTime">Nov 5, 2011</span>
                                            in forum: <a href="#">General</a>
                                        </div>
                                    </div>
                                </li>
                                
                            </ol>
                        </div>
                        
                        <div id="likes" class="tab-pane">
                            <p>Etsy mixtape wayfarers, ethical wes anderson tofu before they sold out mcsweeney's organic lomo retro fanny pack lo-fi farm-to-table readymade. Messenger bag gentrify pitchfork tattooed craft beer, iphone skateboard locavore carles etsy salvia banksy hoodie helvetica. DIY synth PBR banksy irony. Leggings gentrify squid 8-bit cred pitchfork. Williamsburg banh mi whatever gluten-free, carles pitchfork biodiesel fixie etsy retro mlkshk vice blog. Scenester cred you probably haven't heard of them, vinyl craft beer blog stumptown. Pitchfork sustainable tofu synth chambray yr.</p>
                        </div>
                        <div id="comments" class="tab-pane">
                            <p>Trust fund seitan letterpress, keytar raw denim keffiyeh etsy art party before they sold out master cleanse gluten-free squid scenester freegan cosby sweater. Fanny pack portland seitan DIY, art party locavore wolf cliche high life echo park Austin. Cred vinyl keffiyeh DIY salvia PBR, banh mi before they sold out farm-to-table VHS viral locavore cosby sweater. Lomo wolf viral, mustache readymade thundercats keffiyeh craft beer marfa ethical. Wolf salvia freegan, sartorial keffiyeh echo park vegan.</p>
                        </div>
                    </div>
                </div>
            </div>
