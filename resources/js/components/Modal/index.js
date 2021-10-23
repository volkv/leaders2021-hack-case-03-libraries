import React from 'react';
import ReactDOM from 'react-dom';

const Modal = ({ children, onClose }) => {
	const stopPropagation = (event) => {
		event.stopPropagation();
	}

	return ReactDOM.createPortal(
		<div className='modal-shade' onClick={onClose}>
			<div className='modal-scrollable'>
				<div className='modal-container' onClick={stopPropagation}>
					{ children }
				</div>
			</div>
		</div>,
		document.getElementById('root')
	);
}

export default Modal;
