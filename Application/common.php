<?php
/**
 * 公共方法
 *
 * @author syh <794syh940@gmail.com>
 */

/**
 * 私钥解密
 *
 * @param string $code 加密代码
 * @return bool|string    解密成功返回明文, 失败返回false
 */
function private_decode($code)
{
    openssl_private_decrypt(base64_decode($code), $private_decode, \think\Config::get("private_key"));

    return empty($private_decode) ? false : $private_decode;
}

/**
 * 私钥加密
 *
 * @param string $data 明文
 * @return bool|string    加密成功返回密文, 失败返回false
 */
function private_encode($data)
{
    openssl_private_encrypt($data, $private_encode, \think\Config::get("private_key"));

    return empty($private_encode) ? false : base64_encode($private_encode);
}

/**
 * 公钥解密
 *
 * @param string $code 加密代码
 * @return bool|string    解密成功返回明文, 失败返回false
 */
function public_decode($code)
{
    openssl_public_decrypt(base64_decode($code), $public_decode, \think\Config::get("public_key"));

    return empty($public_decode) ? false : $public_decode;
}

/**
 * 公钥加密
 *
 * @param string $data 明文
 * @return bool|string    加密成功返回密文, 失败返回false
 */
function public_encode($data)
{
    openssl_public_encrypt($data, $public_encode, \think\Config::get("public_key"));

    return empty($public_encode) ? false : base64_encode($public_encode);
}