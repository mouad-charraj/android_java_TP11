package com.example.lab11.utils;

import android.content.Context;
import android.content.SharedPreferences;
import androidx.security.crypto.EncryptedSharedPreferences;
import androidx.security.crypto.MasterKey;

public final class SecurePrefs_mouad {
    private static final String PREFS_NAME_mouad = "secure_prefs_mouad";
    private static final String KEY_TOKEN_mouad = "api_token_mouad";

    private SecurePrefs_mouad() {}

    private static SharedPreferences getPrefs_mouad(Context context_mouad) throws Exception {
        MasterKey masterKey_mouad = new MasterKey.Builder(context_mouad)
                .setKeyScheme(MasterKey.KeyScheme.AES256_GCM)
                .build();

        return EncryptedSharedPreferences.create(
                context_mouad,
                PREFS_NAME_mouad,
                masterKey_mouad,
                EncryptedSharedPreferences.PrefKeyEncryptionScheme.AES256_SIV,
                EncryptedSharedPreferences.PrefValueEncryptionScheme.AES256_GCM
        );
    }

    public static void saveToken_mouad(Context context_mouad, String token_mouad) throws Exception {
        getPrefs_mouad(context_mouad).edit().putString(KEY_TOKEN_mouad, token_mouad).apply();
    }

    public static String loadToken_mouad(Context context_mouad) throws Exception {
        return getPrefs_mouad(context_mouad).getString(KEY_TOKEN_mouad, "");
    }
}
