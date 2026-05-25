package com.example.lab11;

import android.Manifest;
import android.content.Context;
import android.content.pm.PackageManager;
import android.location.Location;
import android.location.LocationListener;
import android.location.LocationManager;
import android.os.Bundle;
import android.provider.Settings;
import android.telephony.TelephonyManager;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.app.ActivityCompat;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import com.example.lab11.utils.SecurePrefs_mouad;

import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.HashMap;
import java.util.Locale;
import java.util.Map;

public class MainActivity extends AppCompatActivity {

    private double lat_mouad;
    private double lon_mouad;
    private boolean hasLocation_mouad = false;
    private RequestQueue queue_mouad;
    private TextView tvInfo_mouad;
    private LocationManager locManager_mouad;
    
    // Remplacez par votre IP locale (ex: http://192.168.1.50/localisation/createPosition.php)
    private final String url_mouad = "http://10.0.2.2/localisation/createPosition.php";

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        tvInfo_mouad = findViewById(R.id.mouad_tv_info);
        Button btnSend_mouad = findViewById(R.id.mouad_btn_send);
        Button btnClear_mouad = findViewById(R.id.mouad_btn_clear);
        
        queue_mouad = Volley.newRequestQueue(this);
        locManager_mouad = (LocationManager) getSystemService(Context.LOCATION_SERVICE);

        checkPermissions_mouad();

        btnSend_mouad.setOnClickListener(v -> sendPosition_mouad());
        btnClear_mouad.setOnClickListener(v -> {
            tvInfo_mouad.setText(R.string.mouad_no_location);
            Toast.makeText(this, "Reset effectué", Toast.LENGTH_SHORT).show();
        });
        
        try {
            SecurePrefs_mouad.saveToken_mouad(this, "MOUAD_SECURE_TOKEN_XYZ");
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    private void checkPermissions_mouad() {
        if (ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_FINE_LOCATION) != PackageManager.PERMISSION_GRANTED) {
            ActivityCompat.requestPermissions(this, new String[]{
                    Manifest.permission.ACCESS_FINE_LOCATION,
                    Manifest.permission.READ_PHONE_STATE
            }, 101);
        } else {
            startLocationUpdates_mouad();
        }
    }

    private void startLocationUpdates_mouad() {
        try {
            locManager_mouad.requestLocationUpdates(LocationManager.GPS_PROVIDER, 5000, 5, new LocationListener() {
                @Override
                public void onLocationChanged(@NonNull Location location) {
                    lat_mouad = location.getLatitude();
                    lon_mouad = location.getLongitude();
                    hasLocation_mouad = true;
                    String info_mouad = "Lat: " + lat_mouad + "\nLon: " + lon_mouad + "\nAlt: " + location.getAltitude();
                    tvInfo_mouad.setText(info_mouad);
                }

                @Override
                public void onProviderEnabled(@NonNull String provider) {
                    Toast.makeText(MainActivity.this, getString(R.string.mouad_provider_enabled, provider), Toast.LENGTH_SHORT).show();
                }

                @Override
                public void onProviderDisabled(@NonNull String provider) {
                    Toast.makeText(MainActivity.this, getString(R.string.mouad_provider_disabled, provider), Toast.LENGTH_SHORT).show();
                }
            });
        } catch (SecurityException e) {
            e.printStackTrace();
        }
    }

    private void sendPosition_mouad() {
        if (!hasLocation_mouad) {
            Toast.makeText(this, "Aucune position GPS disponible, patientez...", Toast.LENGTH_SHORT).show();
            return;
        }

        StringRequest request_mouad = new StringRequest(Request.Method.POST, url_mouad,
                response -> Toast.makeText(this, getString(R.string.mouad_success_send), Toast.LENGTH_SHORT).show(),
                error -> Toast.makeText(this, getString(R.string.mouad_error_send), Toast.LENGTH_SHORT).show()) {
            @Override
            protected Map<String, String> getParams() {
                Map<String, String> params_mouad = new HashMap<>();
                SimpleDateFormat sdf_mouad = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss", Locale.getDefault());
                
                params_mouad.put("latitude", String.valueOf(lat_mouad));
                params_mouad.put("longitude", String.valueOf(lon_mouad));
                params_mouad.put("date_position", sdf_mouad.format(new Date()));
                
                String imei_mouad = Settings.Secure.getString(getContentResolver(), Settings.Secure.ANDROID_ID);
                params_mouad.put("imei", imei_mouad);
                
                return params_mouad;
            }
        };
        queue_mouad.add(request_mouad);
    }

    @Override
    public void onRequestPermissionsResult(int requestCode, @NonNull String[] permissions, @NonNull int[] grantResults) {
        super.onRequestPermissionsResult(requestCode, permissions, grantResults);
        if (requestCode == 101 && grantResults.length > 0 && grantResults[0] == PackageManager.PERMISSION_GRANTED) {
            startLocationUpdates_mouad();
        }
    }
}