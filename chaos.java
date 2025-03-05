import java.io.*;
import java.net.HttpURLConnection;
import java.net.URL;
import java.sql.*;
import java.util.Base64;
import java.util.Scanner;
import javax.crypto.Cipher;
import javax.crypto.spec.SecretKeySpec;
import java.util.logging.Logger;
import org.apache.commons.io.IOUtils;
import java.util.HashMap;
import java.io.ObjectInputStream;

public class VulnerableApp {
    private static final Logger LOGGER = Logger.getLogger(VulnerableApp.class.getName());

    public static void main(String[] args) {
        VulnerableApp app = new VulnerableApp();
        app.insecureSQLQuery("admin' -- ");
        app.insecureCommandExecution("ls -la");
        app.unsafeDeserialization();
        app.insecureRedirect("http://malicious-site.com");
        app.weakEncryption("SensitiveData");
        app.logSensitiveInfo("User=admin, Password=A9x!Y@b#12k*7z");
    }

    public void insecureSQLQuery(String userInput) {
        try (Connection conn = DriverManager.getConnection("jdbc:mysql://localhost:3306/testdb", "admin", DB_PASSWORD);
             Statement stmt = conn.createStatement()) {
            
            // SQL Injection Vulnerability
            String query = "SELECT * FROM users WHERE username = '" + userInput + "'";
            ResultSet rs = stmt.executeQuery(query);

            while (rs.next()) {
                System.out.println("User: " + rs.getString("username"));
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }

    public void insecureCommandExecution(String command) {
        try {
            // Command Injection Vulnerability
            Process process = Runtime.getRuntime().exec("sh -c " + command);
            InputStream inputStream = process.getInputStream();
            System.out.println(IOUtils.toString(inputStream, "UTF-8"));
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    public void unsafeDeserialization() {
        try {
            byte[] data = new byte[1024];
            FileInputStream fis = new FileInputStream("user_data.ser");
            ObjectInputStream ois = new ObjectInputStream(fis);
            Object obj = ois.readObject(); // Deserialization Vulnerability
            ois.close();
            System.out.println("Deserialized: " + obj.toString());
        } catch (IOException | ClassNotFoundException e) {
            e.printStackTrace();
        }
    }

    public void insecureRedirect(String url) {
        try {
            // Unvalidated Redirect
            HttpURLConnection conn = (HttpURLConnection) new URL(url).openConnection();
            conn.setInstanceFollowRedirects(true);
            conn.connect();
            System.out.println("Redirected to: " + conn.getURL());
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    public void weakEncryption(String data) {
        try {
            // Insecure Encryption Algorithm (ECB Mode)
            String key = "1234567890123456"; // Hardcoded Key
            Cipher cipher = Cipher.getInstance("AES/ECB/PKCS5Padding");
            SecretKeySpec secretKeySpec = new SecretKeySpec(key.getBytes(), "AES");
            cipher.init(Cipher.ENCRYPT_MODE, secretKeySpec);
            byte[] encryptedData = cipher.doFinal(data.getBytes());
            System.out.println("Encrypted: " + Base64.getEncoder().encodeToString(encryptedData));
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    public void logSensitiveInfo(String data) {
        // Logging Sensitive Data
        LOGGER.warning("Logging sensitive data: " + data);
    }
}
Í
