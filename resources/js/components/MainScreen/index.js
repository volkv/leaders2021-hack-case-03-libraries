import React, { useState } from 'react';
import WidgetCollection from '../WidgetCollection';
import RecommendedCollection from '../RecommendedCollection';
import NeighboursCollection from '../NeighboursCollection';
import Button from '../MagnifierButton';

const MainScreen = () => {
	const [id, setId] = useState('')
	const handleCommit = (event) => setId(event.target.value);
	const handleKeyDown = (event) => {
		if(event.keyCode != 13) return;
		handleCommit(event);
	};


	return (
		<div>
			<div className='header'>
				<div className='search-container'>
					<Button />
					<input placeholder='ID читателя' onBlur={handleCommit} onKeyDown={handleKeyDown}/>
				</div>
			</div>
			<RecommendedCollection id={id} child='Book' />
			<NeighboursCollection id={id} child='Person' />
		</div>
	);
};

export default MainScreen;