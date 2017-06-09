<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta name="viewport" content="width=device-width" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Email Verification - {{ env('BRAND_NAME', 'Origin CMS') }}</title>
	</head>
	<body style="font-family: 'Open Sans', sans-serif;">
		<table style="background-color: #f6f6f6; width: 100%;">
			<tr>
				<td></td>
				<td width="600" style="display: block !important; max-width: 600px !important; margin: 0 auto !important; clear: both !important;">
					<div style="max-width: 600px; margin: 0 auto; display: block; padding: 20px;">
						<table width="100%" cellpadding="0" cellspacing="0" style="background: #fff; border: 1px solid #e9e9e9; border-radius: 3px;">
							<tr>
								<td style="padding: 20px;">
									<table cellpadding="0" cellspacing="0" style="width: 100%;">
										<tr>
											<td style="background-color: #1ab394; color: #ffffff; text-align: center; padding: 40px; font-size: 40px; font-weight: bold;">
												{{ env('BRAND_NAME', 'Origin CMS') }}
											</td>
										</tr>
										<tr>
											<td style="padding: 0 0 20px;">
												<h3 style="color: #5b5b5b;">Account created successfully</h3>
											</td>
										</tr>
										<tr>
											<td style="padding: 0 0 20px; color: #5b5b5b; font-size: 14px;">
												Hi {{ $data['full_name'] }},<br /><br />
												Thanks for creating an account with MyTerroir.<br />
												Please follow the link below to verify your email address<br /><br />
												<div style="text-align: center;">
													<a href="{{ url('/verify/email/' . $data['email_confirmation_code']) }}" style="text-decoration: none; color: #FFF; background-color: #1ab394; border: solid #1ab394; border-width: 5px 10px; line-height: 2; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; border-radius: 5px; text-transform: capitalize;">
														Verify Email Address
													</a>
												</div><br />
												See you soon.
											</td>
										</tr>
										<tr>
											<td style="padding: 0 0 20px; text-align: center;">
												
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</div>
				</td>
				<td></td>
			</tr>
		</table>
	</body>
</html>