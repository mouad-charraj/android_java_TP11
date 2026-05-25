package com.example.lab11.utils;

import android.content.Context;
import java.io.File;
import java.nio.charset.StandardCharsets;
import java.nio.file.Files;

public final class CacheStore_mouad {
    private CacheStore_mouad() {}

    public static void writeCache_mouad(Context context_mouad, String fileName_mouad, String content_mouad) throws Exception {
        File file_mouad = new File(context_mouad.getCacheDir(), fileName_mouad);
        Files.write(file_mouad.toPath(), content_mouad.getBytes(StandardCharsets.UTF_8));
    }

    public static int purgeCache_mouad(Context context_mouad) {
        File[] files_mouad = context_mouad.getCacheDir().listFiles();
        if (files_mouad == null) return 0;
        int deleted_mouad = 0;
        for (File f_mouad : files_mouad) {
            if (f_mouad.delete()) deleted_mouad++;
        }
        return deleted_mouad;
    }
}
