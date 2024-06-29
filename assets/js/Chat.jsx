import React, { useState } from "react";
import axios from "axios";
import Video from "twilio-video";

const Chat = () => {
    const [roomName, setRoomName] = useState('');
    const [hasJoinedRoom, setHasJoinedRoom] = useState(false);
    const [room, setRoom] = useState(null);

    const joinChat = event => {
        event.preventDefault();
        if (roomName) {
            axios.post('/access_token', { roomName }).then((response) => {
                connectToRoom(response.data.token);
            }).catch((error) => {
                console.log(error);
            });
        } else {
            alert("You need to enter a room name");
        }
    };

    const connectToRoom = (token) => {
        const { connect, createLocalVideoTrack } = Video;
        let connectOption = { name: roomName, video: true, audio: true };

        connect(token, connectOption).then(room => {
            setRoom(room);
            setHasJoinedRoom(true);
            console.log(`Successfully joined a Room: ${room}`);
            const videoChatWindow = document.getElementById('video-chat-window');

            createLocalVideoTrack().then(track => {
                videoChatWindow.appendChild(track.attach());
            });

            room.participants.forEach(participant => {
                subscribeToTracks(participant, videoChatWindow);
            });

            room.on('participantConnected', participant => {
                console.log(`Participant "${participant.identity}" connected`);
                subscribeToTracks(participant, videoChatWindow);
            });

        }, error => {
            console.error(`Unable to connect to Room: ${error.message}`);
        });
    };

    const subscribeToTracks = (participant, container) => {
        participant.tracks.forEach(publication => {
            if (publication.isSubscribed) {
                container.appendChild(publication.track.attach());
            }
        });

        participant.on('trackSubscribed', track => {
            container.appendChild(track.attach());
        });
    };

    const leaveChat = () => {
        if (room) {
            const allParticipants = [...room.participants.values(), room.localParticipant];
            allParticipants.forEach(participant => {
                participant.tracks.forEach(publication => {
                    if (publication.track) {
                        publication.track.stop();
                        const attachedElements = publication.track.detach();
                        attachedElements.forEach(element => element.remove());
                    }
                });
            });

            room.disconnect();
            setRoom(null);
            setHasJoinedRoom(false);

            const videoChatWindow = document.getElementById('video-chat-window');
            videoChatWindow.innerHTML = '';

            console.log("Disconnected from the Room");
        }
    };



    return (
        <>
            {!hasJoinedRoom ? (
                <form onSubmit={joinChat}>
                    <div className="flex items-center gap-8">
                        <input
                            type="text"
                            name={'roomName'}
                            id="roomName"
                            value={roomName}
                            placeholder="Entrer un code de room"
                            className="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 disabled:ring-gray-200 sm:text-sm sm:leading-6"
                            onChange={event => setRoomName(event.target.value)}
                        />
                        <button type="submit" className="bg-amber-500 rounded-md text-white px-6 py-1.5">Lancer</button>
                    </div>
                </form>
            ) : (
                <button onClick={leaveChat} className="bg-red-500 rounded-md text-white px-6 py-1.5">Raccrocher</button>
            )}

            <div id="video-chat-window" className="flex flex-wrap items-center justify-center w-full gap-8"></div>
        </>
    );
};

export default Chat;
