import React, { useState } from 'react';
import FacebookLogin from '@greatsumini/react-facebook-login';

function App() {
  const [profile, setProfile] = useState(null);

  return (
    <div style={{ textAlign: 'center', padding: '50px' }}>
      <h1>Facebook Login với React 🚀</h1>
      <hr />

      {!profile ? (
        <div>
          <p>Vui lòng đăng nhập để xem thông tin:</p>
          <FacebookLogin
            appId="912135544906841" 
            onSuccess={(response) => {
              console.log('Đăng nhập thành công:', response);
            }}
            onFail={(error) => {
              console.log('Đăng nhập thất bại:', error);
            }}
            onProfileSuccess={(response) => {
              console.log('Lấy thông tin profile thành công:', response);
              setProfile(response);
            }}
            style={{
              backgroundColor: '#1877f2',
              color: 'white',
              padding: '10px 20px',
              border: 'none',
              borderRadius: '5px',
              fontSize: '16px',
              cursor: 'pointer'
            }}
          />
        </div>
      ) : (
        <div style={{ border: '1px solid #ddd', padding: '20px', display: 'inline-block' }}>
          <h3>Chào mừng, {profile.name}!</h3>
          {profile.picture && (
            <img 
              src={profile.picture.data.url} 
              alt="Avatar" 
              style={{ borderRadius: '50%', marginBottom: '10px' }} 
            />
          )}
          <p>Email: {profile.email}</p>
          <button 
            onClick={() => setProfile(null)}
            style={{ marginTop: '10px', cursor: 'pointer' }}
          >
            Đăng xuất
          </button>
        </div>
      )}
    </div>
  );
}

export default App;