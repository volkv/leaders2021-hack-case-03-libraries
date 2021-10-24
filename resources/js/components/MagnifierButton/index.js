import React from 'react';


const MagnifierButton = (props) => {
	return (
		<div {...props} className={`${props.className ?? ''} button`}>
			<svg width="26" height="27" viewBox="0 0 26 27" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M10.2121 0.998169C12.1534 0.997838 14.0546 1.55069 15.693 2.59196C17.3314 3.63322 18.6391 5.11978 19.4628 6.87747C20.2866 8.63516 20.5924 10.5912 20.3443 12.5164C20.0963 14.4416 19.3046 16.2563 18.0622 17.7478L25.7266 25.4095C25.8911 25.5733 25.9883 25.7927 25.999 26.0246C26.0097 26.2564 25.9332 26.4839 25.7846 26.6622C25.6359 26.8404 25.4258 26.9565 25.1958 26.9876C24.9657 27.0187 24.7324 26.9625 24.5417 26.8301L24.4117 26.7242L16.7492 19.0606C15.4886 20.11 13.9938 20.8403 12.3912 21.1898C10.7886 21.5394 9.12539 21.4979 7.54223 21.0689C5.95908 20.6398 4.50255 19.8359 3.29583 18.725C2.08912 17.6141 1.16771 16.2289 0.609556 14.6867C0.0513985 13.1445 -0.127094 11.4905 0.0891741 9.86474C0.305442 8.23895 0.91011 6.68916 1.85203 5.34643C2.79396 4.00371 4.04543 2.90756 5.5006 2.15069C6.95578 1.39383 8.57184 0.998505 10.2121 0.998169ZM10.2121 2.85511C7.99568 2.85511 5.87002 3.73549 4.30277 5.30259C2.73551 6.86969 1.85504 8.99513 1.85504 11.2113C1.85504 13.4275 2.73551 15.553 4.30277 17.1201C5.87002 18.6872 7.99568 19.5676 10.2121 19.5676C12.4285 19.5676 14.5542 18.6872 16.1215 17.1201C17.6887 15.553 18.5692 13.4275 18.5692 11.2113C18.5692 8.99513 17.6887 6.86969 16.1215 5.30259C14.5542 3.73549 12.4285 2.85511 10.2121 2.85511Z" fill="#828282"/>
			</svg>
		</div>
	);
}

export default MagnifierButton;