 <div class="container" style="margin-top:50px">    
             <?php if (isset($error)): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('success') == TRUE): ?>
                <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
            <?php endif; ?>
             
            <h2>Import data csv </h2>
                <form method="post" action="<?php echo site_url() ?>admin/importdata" enctype="multipart/form-data">
                    <input type="file" name="userfile" ><br><br>
                    <input type="submit" name="submit" value="UPLOAD" class="btn btn-primary">
                </form>
           
           
            
            
<table class="table table-striped table-hover table-bordered">
                <caption>Data List  </caption>
                <thead>
                    <tr>
				    <th>id</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Phone</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($studentlist == FALSE): ?>
                        <tr><td colspan="4">There are currently No Addresses</td></tr>
                    <?php else: ?>
                        <?php foreach ($studentlist as $row): ?>
                            <tr>
						  <td><?php echo $row['idx']; ?></td>
                                <td><?php echo $row['std_prefixname_th']; ?></td>
                                <td><?php echo $row['std_firstname_th']; ?></td>
                                <td><?php echo $row['home_phone']; ?></td> 
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            <hr>
            <footer>
                <p>&copy;Contact</p>
            </footer>
</div>