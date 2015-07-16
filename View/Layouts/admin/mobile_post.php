<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?php $this->BcBaser->title() ?>
        <?php $this->BcBaser->css(array(
            'MobilePost.jquery.mobile.mobilepost.min.css',
            'MobilePost.jquery.mobile.icons.min.css',
            'http://code.jquery.com/mobile/1.4.0-rc.1/jquery.mobile.structure-1.4.0-rc.1.min.css',
            'MobilePost.mobile_post.css'
        )); ?>
        <?php $this->BcBaser->js(array(
            'http://code.jquery.com/jquery-1.10.2.min.js',
            'MobilePost.mobile_configs.js',
            'http://code.jquery.com/mobile/1.4.0-rc.1/jquery.mobile-1.4.0-rc.1.min.js',
            )); ?>
        <?php $this->BcBaser->scripts() ?>
        <script id="panel-init">
            $(function() {
                $( "body>[data-role='panel']" ).panel();
            });
        </script>
    </head>
    <body>
        <div data-role="page" data-theme="a">
            <div data-role="header" data-position="inline">
                <div class="ui-grid-a">
                  <div class="ui-block-a headerLogo">
                   <?php $this->BcBaser->img('admin/btn_logo.png', array('url' => '/mobile_post/mobile_posts/index')); ?>
                  </div>
                  <div class="ui-block-b">
                    <div id="custom-border-radius ui-nodisc-icon" style="vertical-align: middle;text-align:right;">
                    <a href="#rightpanel" class="ui-btn ui-icon-bullets ui-btn-icon-notext ui-corner-all ui-btn-icon-right ui-nodisc-icon" style="background-color:#000; border:0px;" >
                    </a>
                    </div>
                  </div>
                </div>
            </div>
            <!--Content-->
            <?php $this->BcBaser->content() ?>
            <!--/Content-->
            <div data-role="footer" data-position="inline" style="background-color: #333; border:0px;text-align: center;padding:.7em;color:#FFF;text-shadow: none;">
                Mobile Post
            </div>
        </div>
        <!-- rightpanel  -->
        <div data-role="panel" id="rightpanel" data-position="right" data-display="push" data-theme="a" style="background-color:#333;">
            <div class="topContentTitle">
                  MENU
            </div>
            <?php $contentId = $this->BcBaser->getContentsName(true); ?>
            <?php if( $contentId == "MobilePostMobilePostsAdd" ||
                      $contentId == "MobilePostMobilePostsEdit" ||
                      $contentId == "MobilePostMobilePostsPostList"): ?>
            <?php
              echo $this->BcBaser->link('メニューに戻る',array('plugin' => 'mobile_post', 'controller' => 'mobile_posts' , 'action' => 'management', $blogContentID) , array('class'=>'btnBlack sideBtn'));
            ?>
            <?php endif; ?>
            <?php
              echo $this->BcBaser->link('ブログ一覧',array('plugin' => 'mobile_post', 'controller' => 'mobile_posts' , 'action' => 'index') , array('class'=>'btnBlack sideBtn'));
            ?>
            <?php
              echo $this->BcBaser->link('ログアウト',array('plugin' => 'mobile_post', 'controller' => 'mobile_posts' , 'action' => 'logout') , array('class'=>'btnBlack sideBtn'));
            ?>

        </div>
        <!-- /rightpanel -->
    </body>
</html>
