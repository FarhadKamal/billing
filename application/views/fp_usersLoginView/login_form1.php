<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href="<?php echo base_url(); ?>style/style.css" rel="stylesheet" type="text/css" />
	<style>
			Body {
				text-align:center;
				font-family:Verdana, Arial, Helvetica, sans-serif;
				font-size:.71em;
				color:#666666;
			}
			.wrapper {
				width:1000px;
				margin:0 auto;
				text-align:left;
				background-color:#ffffff;
				padding:25px;
			}
			p {
				text-align:justify;
			}
				.style1 {color: #006699}
				.titlebar {
				background-color:#6C8EBE;
				color:#FFFFFF;
			}
			td {
				border:none;
			}
			.Caption {
				font-family: Verdana, Arial, Helvetica, sans-serif;
				font-size: 18px;
				font-weight: bold;
				color: #FFFFFF;
				vertical-align: middle;
			}

		</style>
		<table width='100%' cellspacing='0' cellpadding='0' border='0' align='center'>
				<tr valign="middle" align="center">
					<td valign="top">
						<form method="post" action="<?php echo $action ?>">
							<table border='0' width='400' cellspacing='0' cellpadding='0' id='table' class='BoundaryFull'>
								<tr bgcolor="#088A85">
									<td height='50' align="center" valign="top" class="Caption">User's Login</td>
								</tr>
								<tr>
									<td height="20" align="center" valign="middle" bgcolor="#FFFFFF"><font color="red" size="2"></b><?php echo $error ?></font></b></td>
						        </tr>
								
								<tr>
									<td height="160" align="center" valign="top" bgcolor="#FFFFFF" class="txt1">
					            		<table  border='0' width="96%" id="table7">
									        <tr>
												<td bgcolor="#EBEBEB" height="1"></td>
											</tr>
											<tr>
												<td>
													<table width="100%" id="table8" cellspacing="4">
														<tr>
															<td width="10%"></td>
															<td width="35%" align="left"><b>User Name :</b></td>
															<td width="65%" align="left">
																<input name="user_id" type="text" id="user_id" view="<?php echo $this->validation->user_id; ?>"/>						
			                                                </td>
														</tr>
														<tr>
															<td width="100%" align="center" colspan="3"><?php echo $this->validation->user_id_error; ?></td>
														</tr>
													</table>
											  	</td>
											</tr>
											<tr>
												<td bgcolor="#EBEBEB" height="1"></td>
											</tr>
											<tr>
												<td>
													<table width="100%" id="table9" cellspacing="5">
														<tr>
															<td width="10%"></td>
															<td width="35%" align="left"><b>Password :</b></td>
															<td width="65%" align="left">
																<input name="password" type="password" id="password" view="<?php echo $this->validation->password; ?>"/>
															</td>
														</tr>
														<tr>
															<td width="100%" align="center" colspan="3"><?php echo $this->validation->password_error; ?></td>
														</tr>
													  
													</table>
											  	</td>	
											</tr>
											<tr>
												<td bgcolor="#EBEBEB" height="1"></td>
											</tr>
											<tr>
												<td colspan="3" align="center" valign="bottom" style="padding-right:2px;" >
													<input name="commit" type="submit" value="Log In" />
												</td>
											</tr>
											
										</table>
									</td>
								</tr>	
							</table>
						</form>
					</td>
				</tr>
			</table>
</html>