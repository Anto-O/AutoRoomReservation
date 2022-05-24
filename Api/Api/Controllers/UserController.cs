﻿using Microsoft.AspNetCore.Mvc;
using Dapper;
using MySql.Data.MySqlClient;
using Models;
using System.Text.Json;
using System.Data;

namespace Api.Controllers
{
    [Route("[controller]/[action]")]
    [ApiController]
    public class UserController : Controller
    {
        // Tester le login puis ApartmentController
        private readonly IConfiguration _config;

        private readonly string CS;

        private MySqlConnection Connection { get; set; }

        public UserController(IConfiguration config)
        {
            _config = config;
            CS = _config.GetConnectionString("DB");
            Connection = new(CS);
        }
        
        [HttpGet]
        public string Get([FromQuery] string Id)
        {
            try
            {
                if (string.IsNullOrWhiteSpace(Id))
                {
                    throw new Exception("L'id est null");
                }

                DynamicParameters param = new();
                param.Add(nameof(Id), Id);
                var user = Connection.QuerySingle<User>("user_get", param, commandType: CommandType.StoredProcedure);
                if (Helper.IsObjectNull(user))
                {
                    throw new Exception("Aucun utilisateur ne correspond à cette id");
                }
                var userStr = JsonSerializer.Serialize(user);
                return JsonSerializer.Serialize(new { Success = true, Content = userStr });
            }
            catch (Exception e)
            {
                return JsonSerializer.Serialize(new{Success=false,Error =$"Une erreur est survenue:{e.Message}"});
            }
        }

        [HttpGet]
        public string GetAll()
        {
            try
            {
                var user = Connection.Query<User>("user_get_all",commandType:CommandType.StoredProcedure);
                if (!user.Any())
                {
                    throw new Exception("Aucun utilisateur");
                }
                var userStr = JsonSerializer.Serialize(user);
                return JsonSerializer.Serialize(new { Success = true, Content= userStr });
            }
            catch (Exception e)
            {
                return JsonSerializer.Serialize(new { Success = false, Error = $"Une erreur est survenue : {e.Message}" });
            }
        }

        [HttpPost]
        public async Task<string> Register()
        {
            try
            {
                StreamReader reader = new(Request.Body);
                var str = await reader.ReadToEndAsync();
                if (string.IsNullOrEmpty(str))
                {
                    throw new Exception("La requete est vide");
                }
                var user = JsonSerializer.Deserialize<User>(str);
                if (user==null)
                {
                    throw new Exception("Le body est vide ou malformé");
                }

                user.Id = Guid.NewGuid().ToString();
                DynamicParameters param = new();
                param.AddDynamicParams(user);
                Connection.Execute("user_insert", param, commandType: CommandType.StoredProcedure);
                return JsonSerializer.Serialize(new { Success = true, Error = "" });
            }
            catch (Exception e)
            {
                return JsonSerializer.Serialize(new { Success = false, Error = e.Message });
            }
        }

        [HttpPost]
        public async Task<string> Login([FromQuery] string Email, [FromQuery] string Password)
        {
            try
            {
                if (string.IsNullOrWhiteSpace(Email))
                {
                    throw new Exception("La mail est requis");
                }
                if (string.IsNullOrWhiteSpace(Password))
                {
                    throw new Exception("La mot de passe est requis");
                }
                DynamicParameters param = new();
                param.Add("Email",Email);
                var user = Connection.QuerySingle<User>("user_get_by_mail", param, commandType: CommandType.StoredProcedure);
                if (Helper.IsObjectNull(user))
                {
                    throw new Exception("Aucun utilisateur n'est associé a ce mail");
                }
                if (user.Password==Password)
                {
                    return JsonSerializer.Serialize(new { Success = true, Content = user });
                }

                return JsonSerializer.Serialize(new { Success = false, Error = "Le mot de pass ne correspond pas" });
            }
            catch (Exception e)
            {
                return JsonSerializer.Serialize(new { Success = false, Error = e.Message });
            }
        }

        [HttpGet]
        public bool Remove([FromQuery] string Id)
        {
            try
            {
                DynamicParameters param = new();
                param.Add(nameof(Id), Id);
                var user = Connection.Execute("user_delete", param, commandType: CommandType.StoredProcedure);
                var userStr = JsonSerializer.Serialize(user);
                return true;
            }
            catch (Exception e)
            {
                return false;
            }
        }

        public async Task<string> Update()
        {
            try
            {
                StreamReader reader = new(Request.Body);
                var str = await reader.ReadToEndAsync();
                if (string.IsNullOrEmpty(str))
                {
                    throw new Exception("La requete est vide");
                }
                var user = JsonSerializer.Deserialize<User>(str);
                if (user.Id==null)
                {
                    throw new Exception("L'id est vide");
                }
                
                DynamicParameters param = new();
                param.AddDynamicParams(user);
                Connection.Execute("user_update", param, commandType: System.Data.CommandType.StoredProcedure);
                return JsonSerializer.Serialize(new { Success = true, Error = "" });
            }
            catch (Exception e)
            {
                return JsonSerializer.Serialize(new { Success = false, Error = e.Message });
            }
        }

        [HttpGet]
        public bool SetAdmin([FromQuery] string Id)
        {
            try
            {
                DynamicParameters param = new();
                param.Add(nameof(Id), Id);
                var user = Connection.Execute("user_set_admin", param, commandType: System.Data.CommandType.StoredProcedure);
                return true;
            }
            catch (Exception e)
            {
                return false;
            }
        }

        [HttpGet]
        public bool SetCustomer([FromQuery] string Id)
        {
            try
            {
                DynamicParameters param = new();
                param.Add(nameof(Id), Id);
                var user = Connection.Execute("user_set_cutomer", param, commandType: CommandType.StoredProcedure);
                return true;
            }
            catch (Exception e)
            {
                return false;
            }
        }
    }
}