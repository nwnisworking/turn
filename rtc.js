function data(){
  const aliceConnection = new RTCPeerConnection({
    iceServers : [
    {urls : 'turn:192.168.18.4:9000', username : 'alice', credential : 'test'}
    ],
    iceTransportPolicy : 'relay'

  });
  const bobConnection = new RTCPeerConnection({
      iceServers : [
      {urls : 'turn:192.168.18.4:9000', username : 'bob', credential : 'test'}
    ],
    iceTransportPolicy : 'relay'

  });

  // Log ICE candidates for debugging
  aliceConnection.onicecandidate = event => {
  if (event.candidate) {
  console.log('Alice candidate:', event.candidate);
  bobConnection.addIceCandidate(event.candidate).catch(e => console.error('Error adding Bob candidate:', e));
  }
  };

  bobConnection.onicecandidate = event => {
  if (event.candidate) {
  console.log('Bob candidate:', event.candidate);
  aliceConnection.addIceCandidate(event.candidate).catch(e => console.error('Error adding Alice candidate:', e));
  }
  };

  aliceConnection.createDataChannel('chat')

  aliceConnection.createOffer()
  .then(offer => {
    console.log('Alice created offer:', offer);
    return aliceConnection.setLocalDescription(offer);
  })
  .then(() => {
    return bobConnection.setRemoteDescription(aliceConnection.localDescription);
  })
  .then(() => {
    return bobConnection.createAnswer();
  })
  .then(answer => {
    console.log('Bob created answer:', answer);
    return bobConnection.setLocalDescription(answer);
  })
  .then(() => {
    return aliceConnection.setRemoteDescription(bobConnection.localDescription);
  })
  .catch(error => console.error('Error during connection setup:', error));}

function video(){
  // HTML elements
  const aliceVideo = document.createElement('video');
  const bobVideo = document.createElement('video');
  aliceVideo.autoplay = true;
  bobVideo.autoplay = true;
  document.body.appendChild(aliceVideo);
  document.body.appendChild(bobVideo);

  aliceVideo.volume = 0

  // RTCPeerConnections for Alice and Bob
  const aliceConnection = new RTCPeerConnection({
          iceServers : [
          {urls : 'turn:192.168.18.4:9000', username : 'alice', credential : 'test'}
        ],
        iceTransportPolicy : 'relay'

  });
  const bobConnection = new RTCPeerConnection({
          iceServers : [
          {urls : 'turn:192.168.18.4:9000', username : 'bob', credential : 'test'}
        ],
        iceTransportPolicy : 'relay'

  });

  // Log ICE candidates for debugging
  aliceConnection.onicecandidate = event => {
    if (event.candidate) {
      console.log('Alice candidate:', event.candidate);
      bobConnection.addIceCandidate(event.candidate).catch(e => console.error('Error adding Bob candidate:', e));
    }
  };

  bobConnection.onicecandidate = event => {
    if (event.candidate) {
      console.log('Bob candidate:', event.candidate);
      aliceConnection.addIceCandidate(event.candidate).catch(e => console.error('Error adding Alice candidate:', e));
    }
  };

  // Bob receives the track sent by Alice
  bobConnection.ontrack = event => {
    console.log('Bob received track');
    // Create a MediaStream for Bob if not already created
    if (!bobVideo.srcObject) {
      const remoteStream = new MediaStream();
      remoteStream.addTrack(event.track);
      bobVideo.srcObject = remoteStream;
    } else {
      // If the stream is already created, just add the track
      bobVideo.srcObject.addTrack(event.track);
    }
  };

  // Use getDisplayMedia for screen capture (with audio) and send to Bob
  navigator.mediaDevices.getDisplayMedia({ 
    video: true, 
    audio: true // Capture audio from the display source
  })
  .then(stream => {
    aliceVideo.srcObject = stream;

    // Send all tracks to Bob
    stream.getTracks().forEach(track => {
      aliceConnection.addTrack(track, stream);
    });

    // Create offer from Alice and set up the connections
    return aliceConnection.createOffer();
  })
  .then(offer => {
    console.log('Alice created offer:', offer);
    return aliceConnection.setLocalDescription(offer);
  })
  .then(() => {
    return bobConnection.setRemoteDescription(aliceConnection.localDescription);
  })
  .then(() => {
    return bobConnection.createAnswer();
  })
  .then(answer => {
    console.log('Bob created answer:', answer);
    return bobConnection.setLocalDescription(answer);
  })
  .then(() => {
    return aliceConnection.setRemoteDescription(bobConnection.localDescription);
  })
  .catch(error => console.error('Error during connection setup:', error));
}

setTimeout(video, 1000);