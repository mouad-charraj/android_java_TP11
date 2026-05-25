package com.example.lab11.utils;

import android.content.Context;
import com.example.lab11.model.Student_mouad;
import org.json.JSONArray;
import org.json.JSONObject;
import java.nio.charset.StandardCharsets;
import java.util.ArrayList;
import java.util.List;

public final class InternalStorage_mouad {
    private static final String FILE_NAME_mouad = "students_mouad.json";

    public static void saveStudents_mouad(Context context_mouad, List<Student_mouad> list_mouad) throws Exception {
        JSONArray array_mouad = new JSONArray();
        for (Student_mouad s_mouad : list_mouad) {
            JSONObject obj_mouad = new JSONObject();
            obj_mouad.put("id", s_mouad.id_mouad);
            obj_mouad.put("name", s_mouad.name_mouad);
            obj_mouad.put("age", s_mouad.age_mouad);
            array_mouad.put(obj_mouad);
        }
        String json_mouad = array_mouad.toString();
        context_mouad.openFileOutput(FILE_NAME_mouad, Context.MODE_PRIVATE).write(json_mouad.getBytes(StandardCharsets.UTF_8));
    }

    public static List<Student_mouad> loadStudents_mouad(Context context_mouad) {
        try {
            byte[] bytes_mouad = context_mouad.openFileInput(FILE_NAME_mouad).readAllBytes();
            String json_mouad = new String(bytes_mouad, StandardCharsets.UTF_8);
            JSONArray array_mouad = new JSONArray(json_mouad);
            List<Student_mouad> list_mouad = new ArrayList<>();
            for (int i = 0; i < array_mouad.length(); i++) {
                JSONObject obj_mouad = array_mouad.getJSONObject(i);
                list_mouad.add(new Student_mouad(obj_mouad.getInt("id"), obj_mouad.getString("name"), obj_mouad.getInt("age")));
            }
            return list_mouad;
        } catch (Exception e) {
            return new ArrayList<>();
        }
    }
}
