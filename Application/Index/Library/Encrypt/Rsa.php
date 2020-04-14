<?php
/**
 * RSA 加解密
 *
 * @author syh <794syh940@gmail.com>
 *
 */

namespace app\Index\Library\Encrypt;

class Rsa
{
    /**
     * 公钥
     *
     * @var string
     */
    private static $public_key = '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAyZNzW8eZb6rUxwRJ5Zx2
1oddYlex8tBkF2JOQvcsbyV7HxjqWhoTu0ROZLfX9klqGZK7E5KS3Y9HPs9eI9dF
Yl+o6VJnRzhiYCAQGbyAthZYDHgj0OHuWMkMRfnEF+seY60q2XOZfq4Zj1nSPPz4
qukbaiBW68B8x31yWa03iGchdM+jnI3HwxE00oHCAOJLm+j82VoJuPtYcTrOS6BD
J6K7H4+spRg9YGwWqh+bYF2BvJkM5haSfYVW/AUlFtslsjiDaBC/DM283GGJTnOK
uqQyTo6ftMeajpSEJA6jlDcJsloPtH4Hw5g5wGxqFxEBju9OOld4J4eluRewbpN/
3wIDAQAB
-----END PUBLIC KEY-----';

    /**
     * 私钥
     *
     * @var string
     */
    private static $private_key = '-----BEGIN RSA PRIVATE KEY-----
MIIEpQIBAAKCAQEAyZNzW8eZb6rUxwRJ5Zx21oddYlex8tBkF2JOQvcsbyV7Hxjq
WhoTu0ROZLfX9klqGZK7E5KS3Y9HPs9eI9dFYl+o6VJnRzhiYCAQGbyAthZYDHgj
0OHuWMkMRfnEF+seY60q2XOZfq4Zj1nSPPz4qukbaiBW68B8x31yWa03iGchdM+j
nI3HwxE00oHCAOJLm+j82VoJuPtYcTrOS6BDJ6K7H4+spRg9YGwWqh+bYF2BvJkM
5haSfYVW/AUlFtslsjiDaBC/DM283GGJTnOKuqQyTo6ftMeajpSEJA6jlDcJsloP
tH4Hw5g5wGxqFxEBju9OOld4J4eluRewbpN/3wIDAQABAoIBAQCDHQm39Qh0feWQ
3dVDKJaTy9COGwSAL9Qq8aJ+JFAeq35+J7KnkLas5C0pjftHE1GT+MD+ZiRpQLzS
ugc9wNQ8vIQd35+Vz7MNW02dr+L4jWgQFEUXpR1nWr5E9cT62dAWfZiVgH+iypON
jpsVWGZWpPFUAl0HU1jWWV57vhOVAHeqFNJnqyig+PHaEGpFDJImYf1Ng74EXE9P
Kg2c6/CrF1XUpJcfEuCAPtxIlqWau/CDdZ7Ce66Xt6MhuXczSbHUeQEpRI9PLK//
yXT+HLLBnSoAjQ5OgrWQ9o1PQ5C/PdO6StLVnlOD3nvfQV03xa95vrB0hmzTk6Ad
63HNF5ShAoGBAPMvclyHz0JhVW6iu+I2Q9DDUVuaO9xIY1mABuBq5Kd+HG6kPrsm
ZkLKSUXCSUtELTOa2wmL2wxwJCQlNzeEYbqhqZhHWVZ/pIFN03EP+2VJazSK8V0Q
qcnrsCVivTtWVAjOZqhnOrU3M1eZG2hzNT23l4nXPxbvJYiHpRmSNMxVAoGBANQy
sk/Sb0uh9VA4vlRP8mSJP2Ay1iOXHFVyn7v3fwP+nBS6MM26f1fwudZ1PFggBdz+
LV1chiD45lWK2A9FKrdYRS+TgZJ4kw2ySmuyv6E8kT/iwI4evdLTAch88mCDjvUe
ef2gnI54NCs3pNDF4T8ZrY9HmNcPHHPnB+Li8Y9jAoGAF30LtLhBYo34LHl3YEAR
iMZ0wz5AbeRoBCsDfwWUFEFwCZe/n1/0HJthPGWpoqVxIDzizyc8/xSNyRf3H1uf
/ODEerrZfCtT/lFADPmYNI5E1oxdB7omTCSBnPrOgD60Wy1kaPufhgVQ5jMZnJsU
F2P2NRAfPd3XaDqBzdh7VQkCgYEAzZsVtUw0NLeIJ6Pjn3QbbJijxMVdQfzwIkc/
SPUFqEwy1c6W7TAwpXgFySutPcJBjCDoudalzBr/q5EtypX9TsuDthaZ7N9RtWq0
u2fmUAeMwbcnVk2pJjb4Olf+zc+LXCycjUjriQwlmF6BHx0tJGPwDK2WBYOJ0S/h
L1EwN/sCgYEApr9mu1WbWhtTVmi9kV4Kc3VG3eLVW9SVCJMEkVKDgKEtbZUeg8Dq
RVY/fF39C2ej4trdp9Jtf1p2/bh2BwTMLfcZwY0mNjEVJ5tfx40r9gkmUBD3FbAs
G2ge5x0/KMqwCK5FJtFttQR5GuFlEO2D6BEopQJueX7AfA2parw/oDA=
-----END RSA PRIVATE KEY-----';

    /**
     * 私钥解密
     *
     * @param string $code 加密代码
     * @return bool|string    解密成功返回明文, 失败返回false
     */
    public static function private_decode($code)
    {
        openssl_private_decrypt(base64_decode($code), $private_decode, self::$private_key);
        return empty($private_decode) ? false : $private_decode;
    }

    /**
     * 私钥加密
     *
     * @param string $data 明文
     * @return bool|string    加密成功返回密文, 失败返回false
     */
    public static function private_encode($data)
    {
        openssl_private_encrypt($data, $private_encode, self::$private_key);
        return empty($private_encode) ? false : base64_encode($private_encode);
    }

    /**
     * 公钥解密
     *
     * @param string $code 加密代码
     * @return bool|string    解密成功返回明文, 失败返回false
     */
    public static function public_decode($code)
    {
        openssl_public_decrypt(base64_decode($code), $public_decode, self::$public_key);
        return empty($public_decode) ? false : $public_decode;
    }

    /**
     * 公钥加密
     *
     * @param string $data 明文
     * @return bool|string    加密成功返回密文, 失败返回false
     */
    public static function public_encode($data)
    {
        openssl_public_encrypt($data, $public_encode, self::$public_key);
        return empty($public_encode) ? false : base64_encode($public_encode);
    }

    /**
     * js版本RSA加密中：n，e
     */
    public static function get_public_js_ne()
    {
        $res = openssl_pkey_get_public(self::$public_key);
        $rsa_detail = openssl_pkey_get_details($res)['rsa'];
        pre(['n' => base64_encode($rsa_detail['n']), 'e' => base64_encode($rsa_detail['e'])]);
    }
}