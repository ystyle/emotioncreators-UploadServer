# emotioncreators-upload-server
I社 情感工坊上传下载服务器

<br />
<h1>说明</h1>
因为原本游戏的服务器封国内IP，我尝试着做了这个。<br />
这些代码的功能是给 Illusion 社的 emotioncreators 当一个替代用的上传下载服务器。<br />
这些代码并没有经过测试，所以可能存在BUG<br />
我本人对PHP也是新手，做这个东西也是一边谷歌一边写的，所以不一定能提供代码上的帮助。
<br />
<br />
<br />
<h1>使用注意</h1>
如果你要使用，请注意设置 php.ini 中的 upload_max_filesize、post_max_size、memory_limit。<br />
设置到合理的允许上传文件的大小。<br />
<br />
以及 max_execution_time、max_input_time。<br />
设置一下接受上传、下载的最大时间，以免上传或下载失败。<br />
<br />
mysql 的 sql 文件在 sql 文件夹里面。<br />
data/config.ini 可以修改 mysql 的连接配置。<br />
config.ini 中 thumbnail 开头的缩略图大小。<br />
image_base64 是将图片以 base64 编码储存。<br />
close_error_report 是关闭 php 自带报错，如果开启，可能导致游戏实际使用时出错，建议只在调试时使用。<br />
version 是游戏的版本。<br />
<br />
<br />
<br />

<h1>关于修改游戏连接</h1>
游戏连接地址，在 emotioncreators/DefaultData/url 里面<br />
里面的 dat 就是连接地址，不过都经过加密。<br /><br />

以下是加密代码。<br /><br />

加密 EncryptAES(bytes, "eromake", "phpaddress")<br />
解密 DecryptAES(bytes, "eromake", "phpaddress");<br />

<pre>
<code>
public static byte[] EncryptAES(byte[] srcData, string pw = "illusion", string salt = "unityunity")
{
	RijndaelManaged rijndaelManaged = new RijndaelManaged();
	rijndaelManaged.KeySize = 128;
	rijndaelManaged.BlockSize = 128;
	byte[] bytes = Encoding.UTF8.GetBytes(salt);
	Rfc2898DeriveBytes rfc2898DeriveBytes = new Rfc2898DeriveBytes(pw, bytes);
	rfc2898DeriveBytes.IterationCount = 1000;
	rijndaelManaged.Key = rfc2898DeriveBytes.GetBytes(rijndaelManaged.KeySize / 8);
	rijndaelManaged.IV = rfc2898DeriveBytes.GetBytes(rijndaelManaged.BlockSize / 8);
	ICryptoTransform cryptoTransform = rijndaelManaged.CreateEncryptor();
	byte[] result = cryptoTransform.TransformFinalBlock(srcData, 0, srcData.Length);
	cryptoTransform.Dispose();
	return result;
}

public static byte[] DecryptAES(byte[] srcData, string pw = "illusion", string salt = "unityunity")
{
	RijndaelManaged rijndaelManaged = new RijndaelManaged();
	rijndaelManaged.KeySize = 128;
	rijndaelManaged.BlockSize = 128;
	byte[] bytes = Encoding.UTF8.GetBytes(salt);
	Rfc2898DeriveBytes rfc2898DeriveBytes = new Rfc2898DeriveBytes(pw, bytes);
	rfc2898DeriveBytes.IterationCount = 1000;
	rijndaelManaged.Key = rfc2898DeriveBytes.GetBytes(rijndaelManaged.KeySize / 8);
	rijndaelManaged.IV = rfc2898DeriveBytes.GetBytes(rijndaelManaged.BlockSize / 8);
	ICryptoTransform cryptoTransform = rijndaelManaged.CreateDecryptor();
	byte[] result = cryptoTransform.TransformFinalBlock(srcData, 0, srcData.Length);
	cryptoTransform.Dispose();
	return result;
}
</code>
</pre>
