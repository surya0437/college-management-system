from flask import Flask, Response, render_template_string, jsonify
import cv2
import os

app = Flask(__name__)

# Ensure the 'data' directory exists in the same directory as the script
data_folder = os.path.join(os.path.dirname(os.path.abspath(__file__)), "data")
if not os.path.exists(data_folder):
    os.makedirs(data_folder)

# Counter for images
image_counter = 0
max_images = 20

# Load the face detection classifier and recognizer
classifier = cv2.CascadeClassifier(cv2.data.haarcascades + "haarcascade_frontalface_default.xml")
clf = cv2.face.LBPHFaceRecognizer_create()
classifier_path = os.path.join(os.path.dirname(__file__), "trainedClassifier.xml")
clf.read(classifier_path)

# Function to generate video frames
def gen_frames(user_id):
    global image_counter
    video_capture = cv2.VideoCapture(0)

    while True:
    # while  image_counter < max_images True:
        success, frame = video_capture.read()
        if not success:
            print("Failed to grab frame")
            break
        else:
            gray_frame = cv2.cvtColor(frame, cv2.COLOR_BGR2GRAY)
            faces = classifier.detectMultiScale(gray_frame, scaleFactor=1.1, minNeighbors=5, minSize=(30, 30))

            for (x, y, w, h) in faces:
                # Crop the detected face
                face_roi = gray_frame[y:y+h, x:x+w]

                # Use LBPH Recognizer to predict the face
                label, confidence = clf.predict(face_roi)
                confidence_threshold = 77  # Confidence threshold to consider face recognized

                if confidence > confidence_threshold:
                    # Save the recognized face in the 'data' folder
                    image_path = os.path.join(data_folder, f"user_{user_id}_face_{image_counter:02d}.jpg")
                    cv2.imwrite(image_path, face_roi)
                    image_counter += 1

                    # Draw a rectangle around the recognized face
                    cv2.rectangle(frame, (x, y), (x + w, y + h), (0, 255, 0), 2)
                    cv2.putText(frame, f"ID: {label} Conf: {confidence:.2f}", (x, y - 10),
                                cv2.FONT_HERSHEY_SIMPLEX, 0.5, (0, 255, 0), 2)

            ret, buffer = cv2.imencode('.jpg', frame)
            if not ret:
                print("Failed to encode frame")
                break

            frame = buffer.tobytes()
            yield (b'--frame\r\n' b'Content-Type: image/jpeg\r\n\r\n' + frame + b'\r\n')

    video_capture.release()

# Route to stream the video
@app.route('/video_feed/<user_id>')
def video_feed(user_id):
    return Response(gen_frames(user_id), mimetype='multipart/x-mixed-replace; boundary=frame')

# Route to display the video feed in an HTML page
@app.route('/addFace/<user_id>')
def add_face(user_id):
    return render_template_string('''
        <!doctype html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Video Streaming</title>
        </head>
        <body>
            <h1>Video Streaming</h1>
            <img src="{{ url_for('video_feed', user_id=user_id) }}" width="640" height="480">
            <script>
                const maxImages = {{ max_images }};
                
                const checkImageCount = setInterval(() => {
                    fetch('/image_count')
                        .then(response => response.json())
                        .then(data => {
                            if (data.count >= maxImages) {
                                clearInterval(checkImageCount);
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
    ''', max_images=max_images, user_id=user_id)

# Route to return the current image count
@app.route('/image_count')
def image_count():
    global image_counter
    return jsonify(count=image_counter)

# Route to reset image_counter
@app.route('/reset_counter')
def reset_counter():
    global image_counter
    image_counter = 0
    return jsonify(status="counter_reset")

# Home route
@app.route('/')
def index():
    return render_template_string('''
        <script>window.location.href = "/addFace/15632";</script>
    ''')

if __name__ == "__main__":
    app.run(debug=True, port=5000)
