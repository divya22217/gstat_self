

<?php  if($localadmin =='0' and $_SESSION['menuaccess_codeall'] =='11') {?>
<div class="table-responsive">
                        <table class="table no-margin">
                            <thead>
                                <tr>
                                    <td colspan="8">
                                        <input type="radio" name="c_case" value="1" onChange="javascript:submitForm3();"
                                            <?php if($c_case==1)echo'checked'; ?>><b>Fresh Case for
                                            listing</b>&nbsp;&nbsp;
                                        <input type="radio" name="c_case" value="2" onChange="javascript:submitForm3();"
                                            <?php if($c_case==2)echo'checked'; ?>><b>Document scrutiny for
                                            court</b>&nbsp;&nbsp;
                                        <input type="radio" name="c_case" value="3" onChange="javascript:submitForm3();"
                                            <?php if($c_case==3)echo'checked'; ?>><b>IA Cases</b>&nbsp;&nbsp;
                                        <input type="radio" name="c_case" value="4" onChange="javascript:submitForm3();"
                                            <?php if($c_case==4)echo'checked'; ?>><b>Reports</b>&nbsp;&nbsp;
                                    </td>
                                </tr>
                            </thead>
                        </table>
                    </div>
<?php  } if($localadmin =='0' and $_SESSION['menuaccess_codeall'] =='2') {?>

<div class="box-body">
                            <div class="table-responsive">
                                <table class="table no-margin">
                                    <thead>
                                        <tr>
                                            <th>
                                                <div id="testdiv" style="visibility: visible;">
                                                    <a href="javascript:window.print();">
                                                        <font size="4" color="red">
                                                            Print</font>
                                                    </a>
                                                </div>
                                            </th>
                                            <th>
                                                <input type="radio" name="c_case" value="1"
                                                    onChange="javascript:submitForm3();"
                                                    <?php if ($c_case == 1) {echo 'checked';} ?>><b>Fresh case for
                                                    listing</b>&nbsp;&nbsp;
                                            </th>
                                            <th>
                                                <input type="radio" name="c_case" value="2"
                                                    onChange="javascript:submitForm3();"
                                                    <?php if ($c_case == 2) { echo 'checked'; } ?>><b>Document
                                                    scrutiny forcourt</b>&nbsp;&nbsp;
                                            </th>
                                            <th>
                                                <input type="radio" name="c_case" value="3"
                                                    onChange="javascript:submitForm3();"
                                                    <?php if ($c_case == 3) { echo 'checked'; }?>><b>IA</b>
                                            </th>
                                            <th>
                                                <input type="radio" name="c_case" value="4"
                                                    onChange="javascript:submitForm3();"
                                                    <?php if ($c_case == 4) {echo 'checked'; } ?>><b>Reports</b>
                                            </th>

                                            <th>
                                                <input type="radio" name="c_case" value="5"
                                                    onChange="javascript:submitForm3();"
                                                    <?php if ($c_case == 5) {echo 'checked';} ?>><b>Defective case
                                                    for listing</b>&nbsp;&nbsp;
                                            </th>

                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
   <?php } ?>