import React, {useState, useEffect } from 'react';
import { BrowserRouter as Router, Switch, Route } from 'react-router-dom';
import Content from './components/Content';
import Logo from './components/Logo';
import Header from './components/Header';
import Navigation from './components/Navigation';
import LayoutContainer from './components/LayoutContainer';
import LayoutContent from './components/LayoutContent';
import LayoutNavigation from './components/LayoutNavigation';

const App = () => {
    return (
        <Router>
            <LayoutContainer>
                <LayoutNavigation>
                    <Logo />
                    <Navigation />
                </LayoutNavigation>
                <LayoutContent>
                    <Header />
                    <Content>
                        <Switch>
                            <Route path='/about'>
                                <span>about</span>
                            </Route>
                            <Route path='/'>
                                <span>default</span>
                            </Route>
                        </Switch>
                    </Content>
                </LayoutContent>
            </LayoutContainer>
        </Router>
    );
};

export default App;