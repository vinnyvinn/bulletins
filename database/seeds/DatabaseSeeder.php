<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $this->call(PermissionsTableSeeder::class);
         $this->call(UsersTableSeeder::class);
         $this->call(PassportSeeder::class);
         $this->call(IntegrationsSeeder::class);
         $this->call(CarriagePointsSeeder::class);
         $this->call(CargoSeeder::class);
         $this->call(MakesAndModelsSeeder::class);
//         $this->call(MileageTypeSeeder::class);
//         $this->call(DevelopmentSeeder::class);
    }
}

// for mapping
//
//update vehicles set truck_id = case id
//
//when 213 then 125
//when 214 then 67
//when 216 then 11
//when 217 then 47
//when 217 then 12
//when 218 then 66
//when 434 then 159
//when 219 then 34
//when 220 then 73
//when 221 then 53
//when 221 then 197
//when 436 then 21
//when 436 then 109
//when 437 then 27
//when 438 then 126
//when 225 then 167
//when 226 then 65
//when 227 then 77
//when 228 then 15
//when 229 then 198
//when 230 then 48
//when 230 then 205
//when 231 then 169
//when 232 then 30
//when 233 then 32
//when 233 then 78
//when 234 then 101
//when 236 then 114
//when 238 then 20
//when 244 then 179
//when 247 then 54
//when 248 then 80
//when 249 then 43
//when 250 then 50
//when 251 then 71
//when 252 then 46
//when 254 then 44
//when 255 then 45
//when 256 then 57
//when 256 then 83
//when 257 then 172
//when 258 then 165
//when 259 then 56
//when 260 then 51
//when 261 then 189
//when 262 then 187
//when 263 then 168
//when 264 then 55
//when 264 then 75
//when 265 then 49
//when 266 then 52
//when 269 then 118
//when 270 then 135
//when 273 then 121
//when 274 then 124
//when 275 then 130
//when 280 then 120
//when 282 then 133
//when 378 then 127
//when 379 then 115
//when 383 then 136
//when 291 then 122
//when 385 then 119
//when 294 then 35
//when 296 then 117
//when 298 then 2
//when 390 then 37
//when 392 then 38
//when 393 then 123
//when 300 then 166
//when 301 then 59
//when 303 then 173
//when 305 then 58
//when 306 then 162
//when 307 then 161
//when 310 then 81
//when 311 then 61
//when 312 then 64
//when 313 then 63
//when 314 then 74
//when 315 then 62
//when 316 then 82
//when 317 then 69
//when 318 then 76
//when 319 then 79
//when 320 then 72
//when 321 then 70
//when 322 then 68
//when 323 then 84
//when 324 then 60
//when 396 then 198
//when 397 then 160
//when 398 then 195
//when 399 then 186
//when 400 then 202
//when 401 then 184
//when 403 then 192
//when 326 then 112
//when 327 then 110
//when 328 then 207
//when 329 then 201
//when 330 then 203
//when 331 then 199
//when 332 then 209
//when 333 then 185
//when 334 then 180
//when 335 then 183
//when 404 then 188
//when 405 then 204
//when 337 then 200
//when 338 then 187
//when 339 then 194
//when 340 then 206
//when 341 then 191
//when 342 then 181
//when 343 then 182
//when 345 then 208
//when 346 then 163
//when 347 then 190
//when 348 then 90
//when 349 then 86
//when 350 then 102
//when 351 then 103
//when 352 then 88
//when 353 then 87
//when 354 then 105
//when 355 then 85
//when 356 then 104
//when 357 then 93
//when 601 then 170
//when 602 then 164
//when 603 then 171
//when 358 then 106
//when 359 then 97
//when 360 then 108
//when 361 then 92
//when 362 then 99
//when 363 then 98
//when 364 then 91
//when 365 then 107
//when 366 then 96
//when 367 then 95
//when 368 then 94
//when 369 then 89
//when 371 then 100
//
//end
//
//
//
//
//
//
//update vehicles set trailer_id = case id
//when 125	then 213
//when 67	then 214
//when 11	then 216
//when 47	then 217
//when 12	then 217
//when 66	then 218
//when 159	then 434
//when 34	then 219
//when 73	then 220
//when 53	then 221
//when 197	then 221
//when 21	then 436
//when 109	then 436
//when 27	then 437
//when 126	then 438
//when 167	then 225
//when 65	then 226
//when 77	then 227
//when 15	then 228
//when 198	then 229
//when 48	then 230
//when 205	then 230
//when 169	then 231
//when 30	then 232
//when 32	then 233
//when 78	then 233
//when 101	then 234
//when 114	then 236
//when 20	then 238
//when 179	then 244
//when 54	then 247
//when 80	then 248
//when 43	then 249
//when 50	then 250
//when 71	then 251
//when 46	then 252
//when 44	then 254
//when 45	then 255
//when 57	then 256
//when 83	then 256
//when 172	then 257
//when 165	then 258
//when 56	then 259
//when 51	then 260
//when 189	then 261
//when 187	then 262
//when 168	then 263
//when 55	then 264
//when 75	then 264
//when 49	then 265
//when 52	then 266
//when 118	then 269
//when 135	then 270
//when 121	then 273
//when 124	then 274
//when 130	then 275
//when 120	then 280
//when 133	then 282
//when 127	then 378
//when 115	then 379
//when 136	then 383
//when 122	then 291
//when 119	then 385
//when 35	then 294
//when 117	then 296
//when 2	then 298
//when 37	then 390
//when 38	then 392
//when 123	then 393
//when 166	then 300
//when 59	then 301
//when 173	then 303
//when 58	then 305
//when 162	then 306
//when 161	then 307
//when 81	then 310
//when 61	then 311
//when 64	then 312
//when 63	then 313
//when 74	then 314
//when 62	then 315
//when 82	then 316
//when 69	then 317
//when 76	then 318
//when 79	then 319
//when 72	then 320
//when 70	then 321
//when 68	then 322
//when 84	then 323
//when 60	then 324
//when 198	then 396
//when 160	then 397
//when 195	then 398
//when 186	then 399
//when 202	then 400
//when 184	then 401
//when 192	then 403
//when 112	then 326
//when 110	then 327
//when 207	then 328
//when 201	then 329
//when 203	then 330
//when 199	then 331
//when 209	then 332
//when 185	then 333
//when 180	then 334
//when 183	then 335
//when 188	then 404
//when 204	then 405
//when 200	then 337
//when 187	then 338
//when 194	then 339
//when 206	then 340
//when 191	then 341
//when 181	then 342
//when 182	then 343
//when 208	then 345
//when 163	then 346
//when 190	then 347
//when 90	then 348
//when 86	then 349
//when 102	then 350
//when 103	then 351
//when 88	then 352
//when 87	then 353
//when 105	then 354
//when 85	then 355
//when 104	then 356
//when 93	then 357
//when 170	then 601
//when 164	then 602
//when 171	then 603
//when 106	then 358
//when 97	then 359
//when 108	then 360
//when 92	then 361
//when 99	then 362
//when 98	then 363
//when 91	then 364
//when 107	then 365
//when 96	then 366
//when 95	then 367
//when 94	then 368
//when 89	then 369
//when 100	then 371
//end
