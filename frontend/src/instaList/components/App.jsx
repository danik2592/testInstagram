import React, { Component } from 'react';
import Card from '@material-ui/core/Card';
import CardActionArea from '@material-ui/core/CardActionArea';
import CardActions from '@material-ui/core/CardActions';
import CardContent from '@material-ui/core/CardContent';
import CardMedia from '@material-ui/core/CardMedia';
import Button from '@material-ui/core/Button';
import Typography from '@material-ui/core/Typography';
import Carousel from 'react-instagram-carousel';
import './../styles/style.less';

class App extends Component {
  static propTypes = {

  }

  constructor(props) {
    super(props);
    this.state = {
      posts: [],
      play: false
    }

  }

  componentDidMount() {
    console.log('componentDidMount');
    fetch('insta-posts/get-posts')
        .then(response => response.json())
        .then(data => {
          console.log(data);
          this.setState({ posts: data })
        });
  }

  render() {
    const { posts, play } = this.state;
    const listPost = posts.map((post, ind) => {
      const images = post.postMedia.map((m) => m.url);
      if((ind + 1)%3 === 0){
          return (
              <div key={ind} className="row">
              <Card key={post.id} className='col-md-3' style={{ margin: '15pt'}}>
                  <CardActionArea>
                      {post.type === 'GraphSidecar' && <Carousel style={{ height: "200pt" }} images={images} />}
                      {post.type !== 'GraphSidecar' && <CardMedia
                          component={post.type === "GraphVideo" ? "video" : "img"}
                          src={post.postMedia[0].url}
                          controls
                          // title='Contemplative Reptile'
                      />}
                      <CardContent>
                          <Typography variant='body2' color='textSecondary' component='p'>
                              {post.text}
                          </Typography>
                      </CardContent>
                  </CardActionArea>
              </Card>
              </div>
          );
      }
      return (
          <Card key={post.id} className='col-md-3' style={{ margin: '15pt'}}>
              <CardActionArea>
                  {post.type === 'GraphSidecar' && <Carousel style={{ height: "200pt" }} images={images} />}
                  {post.type !== 'GraphSidecar' && <CardMedia
                      component={post.type === "GraphVideo" ? "video" : "img"}
                      src={post.postMedia[0].url}
                      controls
                      // title='Contemplative Reptile'
                  />}
                  <CardContent>
                      <Typography variant='body2' color='textSecondary' component='p'>
                          {post.text}
                      </Typography>
                  </CardContent>
              </CardActionArea>
          </Card>
      );
    });
    return (
        <div className="row">
          {listPost}
        </div>
    );
  }

}
export default App;
