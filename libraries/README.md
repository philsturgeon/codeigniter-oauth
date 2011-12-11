# Fuel OAuth

An implementation of the [OAuth](http://oauth.net/) protocol with drivers to work with different providers such as Twitter, Google, etc.

This is based on the wonderful [Kohana OAuth](https://github.com/kohana/oauth) package but has been adapted to work with a wider range of providers.

Note that this Cell ONLY provides the authorization mechanism. You will need to implement the example controller so you can save this information to make API requests on the users behalf.

## Providers

- Dropbox
- Flickr
- Google
- LinkedIn
- Twitter