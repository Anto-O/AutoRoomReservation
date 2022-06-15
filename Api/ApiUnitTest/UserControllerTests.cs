
using Microsoft.Extensions.Configuration;
using Models;
using System.Diagnostics;
using System.Text.Json;
using Microsoft.AspNetCore.Http;
using NUnit.Framework;

namespace ApiUnitTest
{
    
    public class UserControllerTests
    {
        //private readonly IConfiguration _config;

        private UserController _controller;

        private const string _emailTest = "test.user@mailtest.com";
        private const string _pwdTest = "pwdDeTest";
        private const string _adminToken = "3bee6a14-ab54-44f2-a2b7-ffacbc4fb767/070a02e2-3fff-49dd-8660-b776ff599ffc";

        private static string? _userId = null;

        public UserControllerTests()
        {
            var myConfiguration = new Dictionary<string, string>
            {   
                {"ConnectionStrings:DB", "SERVER=localhost;DATABASE=autoroom_test;UID=root;PASSWORD=;"},
            };
            var httpContext = new DefaultHttpContext();
            httpContext.Request.Headers["User-Id"] = _adminToken;

            var configuration = new ConfigurationBuilder()
                .AddInMemoryCollection(myConfiguration)
                .Build();
            _controller = new(configuration)
            {
                ControllerContext = new()
                {
                    HttpContext = httpContext
                }
            };
        }
        
        [Test, Order(1)]
        public async Task Register_New_User_Valid()
        {
            User user = new()
            {
                Email = _emailTest,
                Password = _pwdTest,
                FirstName = "Test",
                LastName = "Test",
                Phone = "0700000000",
                BirthDate = DateTime.Now,
                Nationality = "Test"
            };

            var resStr = await _controller.Register(user);
            var res = JsonSerializer.Deserialize<ApiRes>(resStr);
            _userId = res.Content;
            Assert.Multiple(() =>
            {
                Assert.That(res.Success, Is.True);
                Assert.That(res.Error, Is.EqualTo(""));
            });
        }

        [Test, Order(2)]
        public async Task Register_New_User_Invalid_Field()
        {
            User user = new()
            {
                Email = _emailTest,
                Password = _pwdTest,
                FirstName = "",
                LastName = "Test",
                Phone = "0700000000",
                BirthDate = DateTime.Now,
                Nationality = "Test"
            };

            var resStr = await _controller.Register(user);
            var res = JsonSerializer.Deserialize<ApiRes>(resStr);
            Assert.Multiple(() =>
            {
                Assert.That(res.Success, Is.False);
                Assert.That(res.Error, Is.EqualTo("Tout les champs sont requis"));
            });
        }

        [Test, Order(3)]
        public async Task Register_New_User_Duplicate_Mail()
        {
            User user = new()
            {
                Email = _emailTest,
                Password = _pwdTest,
                FirstName = "aaa",
                LastName = "Test",
                Phone = "0700000000",
                BirthDate = DateTime.Now,
                Nationality = "Test"
            };

            var resStr = await _controller.Register(user);
            var res = JsonSerializer.Deserialize<ApiRes>(resStr);
            Assert.Multiple(() =>
            {
                Assert.That(res.Success, Is.False);
                Assert.That(res.Error, Is.EqualTo("Duplicate entry 'test.user@mailtest.com' for key 'user.email'"));
                //Duplicate entry 'test.user@mailtest.com' for key 'user.email
            });
        }

        [Test, Order(4)]
        public async Task Update_User_Valid()
        {
            User user = new()
            {
                Id = _userId,
                Email = _emailTest,
                Password = _pwdTest,
                FirstName = "NewFirstName",
                LastName = "Test",
                Phone = "0700000000",
                BirthDate = DateTime.Now,
                Nationality = "Test"
            };
            var resStr = await _controller.Update(user);
            var res = JsonSerializer.Deserialize<ApiRes>(resStr);
            Assert.Multiple(() =>
            {
                Assert.That(res.Success, Is.True);
                Assert.That(res.Error, Is.EqualTo(""));
            });
        }

        [Test, Order(5)]
        public async Task Update_User_Invalid_Id()
        {
            User user = new()
            {
                Email = _emailTest,
                Password = _pwdTest,
                FirstName = "NewFirstName",
                LastName = "Test",
                Phone = "0700000000",
                BirthDate = DateTime.Now,
                Nationality = "Test"
            };
            var resStr = await _controller.Update(user);
            var res = JsonSerializer.Deserialize<ApiRes>(resStr);
            Assert.Multiple(() =>
            {
                Assert.That(res.Success, Is.False);
                Assert.That(res.Error, Is.EqualTo("L\u0027id est vide"));
                //L'id est vide
            });
        }

        [Test, Order(5)]
        public void Get_User_Valid()
        {
            var resStr = _controller.Get(_userId);
            var res = JsonSerializer.Deserialize<ApiRes>(resStr);
            Assert.Multiple(() =>
            {
                Assert.That(res.Success, Is.True);
                //Assert.That(res.Error, Is.EqualTo(""));
                Assert.That(res.User.Id, Is.EqualTo(_userId));
            });
        }

        [Test, Order(5)]
        public void Get_User_Invalid()
        {
            var resStr = _controller.Get("FakeId");
            var res = JsonSerializer.Deserialize<ApiRes>(resStr);
            Assert.Multiple(() =>
            {
                Assert.That(res.Success, Is.False);
                Assert.That(res.Error, Is.EqualTo("Une erreur est survenue:Sequence contains no elements"));
            });
        }

        [Test, Order(5)]
        public void Get_All_User_Valid()
        {
            var resStr = _controller.GetAll();
            var res = JsonSerializer.Deserialize<ApiRes>(resStr);
            Assert.Multiple(() =>
            {
                Assert.That(res.Success, Is.True);
                Assert.That(res.Users, Has.Count.EqualTo(1));
            });
        }

        [Test, Order(6)]
        public async Task Login_User_Valid()
        {
            User user = new()
            {
                Email = _emailTest,
                Password = _pwdTest
            };
            var resStr = await _controller.Login(user);

            var res = JsonSerializer.Deserialize<ApiRes>(resStr);
            Assert.Multiple(() =>
            {
                Assert.That(res.Success, Is.True);
                Assert.That(res.User.Id, Is.Not.EqualTo(""));
            });

        }

        [Test, Order(7)]
        public async Task Login_User_Invalid()
        {
            User user = new()
            {
                Email = _emailTest
            };
            var resStr = await _controller.Update(user);

            var res = JsonSerializer.Deserialize<ApiRes>(resStr);
            Assert.Multiple(() =>
            {
                Assert.That(res.Success, Is.False);
                Assert.That(res.Error, Is.EqualTo("L'id est vide"));
                //L'id est vide
            });

        }

        [Test, Order(8)]
        public void Delete_User_Valid()
        {
            var resStr = _controller.Remove(_userId);
            var res = JsonSerializer.Deserialize<ApiRes>(resStr);
            Assert.Multiple(() =>
            {
                Assert.That(res.Success, Is.True);
                Assert.That(res.Error, Is.EqualTo(""));
            });
        }

        [Test, Order(9)]
        public void Delete_User_Invalid()
        {
            var resStr = _controller.Remove("FakeId");

            var res = JsonSerializer.Deserialize<ApiRes>(resStr);
            Assert.Multiple(() =>
            {
                Assert.That(res.Success, Is.False);
                Assert.That(res.Error, Is.EqualTo("Aucun utilisateur ne correspond à cette id"));
                //Aucun utilisateur ne correspond à cette id
            });

        }
    }
}
