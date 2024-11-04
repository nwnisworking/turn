# TURN PHP Server
TURN server for WebRTC. The project creates a server that listens and communicate with the client socket.

The server is created using UDP master socket that listens to all incoming messages when the client creates a RTCPeerConnection that links to an iceServer. The way it works as of now allow user to stream and data channels. There is still no authentication unfortunately.
