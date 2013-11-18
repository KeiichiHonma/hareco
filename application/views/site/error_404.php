<?php $this->load->view('layout/header/header'); ?>
            <div id="contents">
                <div id="contents_inner">
                    <!-- ■ MAIN CONTENTS ■ -->
                    <div id="page_w">
                        <div class="c_wrapper_w">
                            <h1 class="l1">404 File not found.</h1>
                                <table style="width:100%;height:400px;">
                                    <tr>
                                        <td align="center">
                                        <img src="/images/404<?php echo $this->config->item('language_min'); ?>.png" />
                                        </td>
                                    </tr>
                                </table>

                        </div>
                        <!--/c_wrapper-->
                    </div>
                    <!--/page-->
                </div>
            </div>
            <!--/contents-->
<?php $this->load->view('layout/footer/footer'); ?>
