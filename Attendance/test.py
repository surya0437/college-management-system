from flask import (
    Flask,
    Response,
    render_template_string,
    jsonify,
    flash,
    redirect,
    url_for,
    get_flashed_messages,
)
import cv2
import os

app = Flask(__name__)

# Use a generated secret key
app.secret_key = os.urandom(24)  # More secure, generates a new key each time the app starts


# Ensure the 'data' directory exists in the same directory as the script
data_folder = os.path.join(os.path.dirname(os.path.abspath(__file__)), "data")
if not os.path.exists(data_folder):
    os.makedirs(data_folder)

# Counter for images
image_counter = 0
max_images = 20

def gen_frames(user_id):
    global image_counter
    image_counter = 0
    video_capture = cv2.VideoCapture(0)
    if not video_capture.isOpened():
        flash("Error: Could not open video device", "error")
        return None

    while image_counter < max_images:
        success, frame = video_capture.read()
        if not success:
            flash("Failed to grab frame", "error")
            break
        else:
            # Save the frame to the 'data' folder with the user_id in the filename
            image_path = os.path.join(data_folder, f"user_{user_id}_image_{image_counter:02d}.jpg")
            cv2.imwrite(image_path, frame)
            image_counter += 1

            # Encode the frame in JPEG format
            ret, buffer = cv2.imencode(".jpg", frame)
            if not ret:
                flash("Failed to encode frame", "error")
                break
            frame = buffer.tobytes()

            yield (b"--frame\r\n" b"Content-Type: image/jpeg\r\n\r\n" + frame + b"\r\n\r\n")

    video_capture.release()

@app.route("/video_feed/<user_id>")
def video_feed(user_id):
    frames_generator = gen_frames(user_id)
    if frames_generator is None:
        return redirect(url_for("add_face", user_id=user_id))
    return Response(frames_generator, mimetype="multipart/x-mixed-replace; boundary=frame")

@app.route("/addFace/<user_id>")
def add_face(user_id):
    return render_template_string(
        """
        <!doctype html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Video Streaming</title>
        </head>
        <body>
            <h1>Video Streaming</h1>
            <p class="error">
                {% with messages = get_flashed_messages(with_categories=true) %}
                    {% if messages %}
                        <ul>
                        {% for category, message in messages %}
                            <li>{{ message }}</li>
                        {% endfor %}
                        </ul>
                    {% endif %}
                {% endwith %}
            </p>
            <img src="{{ url_for('video_feed', user_id=user_id) }}" width="640" height="480">
            <script>
                const maxImages = {{ max_images }};
                
                const checkImageCount = setInterval(() => {
                    fetch('/image_count')
                        .then(response => response.json())
                        .then(data => {
                            if (data.count >= maxImages) {
                                clearInterval(checkImageCount);
                                window.location.href = "https://github.com"; // Redirect to GitHub
                            }
                        }).catch(error => console.error('Error:', error));
                }, 1000); // Check every second
                
                document.addEventListener('keydown', function(event) {
                    if (event.keyCode === 13) {
                        window.location.reload();
                    }
                });
            </script>
        </body>
        </html>
    """,
        max_images=max_images,
        user_id=user_id,
    )

@app.route("/image_count")
def image_count():
    global image_counter
    return jsonify(count=image_counter)

@app.route("/")
def index():
    return render_template_string(
        """<script>window.location.href = "/addFace/15632";</script>"""
    )

if __name__ == "__main__":
    app.run(debug=True, port=5000)
